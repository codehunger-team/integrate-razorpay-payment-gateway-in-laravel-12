<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $apiKey = env('RAZORPAY_API_KEY');
        $apiSecret = env('RAZORPAY_API_SECRET');

        $response = Http::withBasicAuth($apiKey, $apiSecret)->post('https://api.razorpay.com/v1/orders', [
            'amount' => 50000, // amount in paise => ₹500.00
            'currency' => 'INR',
            'receipt' => 'rcptid_11',
            'payment_capture' => 1
        ]);

        $order = $response->json();

        return view('payment.razorpay', [
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'apiKey' => $apiKey
        ]);
    }

    public function verifySignature(Request $request)
    {
        $signature = $request->razorpay_signature;
        $orderId = $request->razorpay_order_id;
        $paymentId = $request->razorpay_payment_id;

        $generatedSignature = hash_hmac('sha256', "$orderId|$paymentId", env('RAZORPAY_API_SECRET'));

        if (hash_equals($generatedSignature, $signature)) {
            return response()->json(['status' => 'success', 'message' => 'Payment verified successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Signature mismatch! Payment failed.']);
        }
    }
}

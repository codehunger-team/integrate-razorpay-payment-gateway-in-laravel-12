<!DOCTYPE html>
<html>
<head>
    <title>Pay with Razorpay</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .payment-card {
            background: #fff;
            padding: 2rem 3rem;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            text-align: center;
        }
        .payment-card h2 {
            margin-bottom: 1rem;
            color: #333;
        }
        .payment-card button {
            background-color: #528FF0;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }
        #message {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <h2>Complete Your Payment</h2>
        <p>Amount: ₹{{ number_format($amount / 100, 2) }}</p>
        <button id="rzp-button">Pay Now</button>
        <p id="message"></p>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let options = {
            "key": "{{ $apiKey }}",
            "amount": "{{ $amount }}",
            "currency": "INR",
            "name": "CodeHunger",
            "description": "Laravel Razorpay Payment",
            "order_id": "{{ $order_id }}",
            "handler": function (response) {
                fetch("{{ route('payment.verify') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(response)
                })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById("message");
                    msg.innerText = data.message;
                    msg.style.color = data.status === 'success' ? 'green' : 'red';
                });
            },
            "theme": {
                "color": "#528FF0"
            }
        };

        const rzp = new Razorpay(options);

        document.getElementById('rzp-button').onclick = function(e) {
            rzp.open();
            e.preventDefault();
        };
    </script>
</body>
</html>

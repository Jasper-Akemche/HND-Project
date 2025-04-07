<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page - JN Delivery</title>
    <style>
                .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0.5;
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
        }

        .logo {
            font-size: 1.8em;
            font-weight: 700;
            color: var(--primary-color);
        }

        .nav-menu {
            display: flex;
            list-style: none;
        }

        .nav-menu li {
            margin-left: 30px;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: var(--secondary-color);
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #f9f9f9;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0.5;
        }

        main {
            padding: 20px;
        }

        .order-summary {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-summary h2 {
            margin-top: 0.5;
        }

        .payment-form {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-form h2 {
            margin-top: 0;
        }

        .payment-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .payment-form input, .payment-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .payment-form button {
            width: 100%;
            padding: 15px;
            background-color:midnightblue;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .payment-form button:hover {
            background-color:darkblue;
        }

        footer {
            background-color: #2C3E50;
            color: white;
            padding: 15px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0.5;
        }

        .payment-methods {
            margin-top: 20px;
        }

        .payment-methods label {
            font-weight: normal;
            display: inline-block;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <header>
        <header class="header">
        <div class="nav-container">
            <div class="logo">JN Delivery - Payment</div>
            <ul class="nav-menu">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="signup.php">Signup</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </header>
    </header>

    <main>
        <section>
        <div class="delivery-form">
        <h2>Order Summary</h2>
        <form action="process_request.php" method="POST">
    </div>
        </section>

        <section class="payment-form">
            <h2>Payment Information</h2>
            <form action="/process-payment" method="POST">
                <label for="payment-method">Select Payment Method</label>
                <select id="payment-method" name="payment_method" required>
                    <option value="credit-card">Credit Card</option>
                    <option value="mtn">MTN Mobile Money</option>
                    <option value="orange">Orange Mobile Money</option>
                </select>

                <div id="credit-card-details" class="payment-methods">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card_number" placeholder="Enter Card Number" required>
                    
                    <label for="expiry-date">Expiry Date</label>
                    <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" required>
                    
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required>
                </div>

                <div id="mtn-payment" class="payment-methods" style="display:none;">
                    <label for="mtn-phone">MTN Mobile Number</label>
                    <input type="text" id="mtn-phone" name="mtn_phone" placeholder="Enter your MTN number" required>
                </div>

                <div id="orange-payment" class="payment-methods" style="display:none;">
                    <label for="orange-phone">Orange Mobile Number</label>
                    <input type="text" id="orange-phone" name="orange_phone" placeholder="Enter your Orange number" required>
                </div>

                <button type="submit">Pay Now</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 JN Delivery</p>
    </footer>

    <script>
        document.getElementById('payment-method').addEventListener('change', function() {
            var paymentMethod = this.value;
            if (paymentMethod === 'credit-card') {
                document.getElementById('credit-card-details').style.display = 'block';
                document.getElementById('mtn-payment').style.display = 'none';
                document.getElementById('orange-payment').style.display = 'none';
            } else if (paymentMethod === 'mtn') {
                document.getElementById('credit-card-details').style.display = 'none';
                document.getElementById('mtn-payment').style.display = 'block';
                document.getElementById('orange-payment').style.display = 'none';
            } else if (paymentMethod === 'orange') {
                document.getElementById('credit-card-details').style.display = 'none';
                document.getElementById('mtn-payment').style.display = 'none';
                document.getElementById('orange-payment').style.display = 'block';
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fast Delivery - Delivery Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #3498DB;
            --accent-color: #E74C3C;
            --background-light: #F4F6F7;
            --text-color: #2C3E50;
            --white: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-light);
        }

        /* Navigation Styling */
        .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
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

        /* Hero Section */
        

        /* Services Section */
        .services {
            padding: 100px 20px;
            text-align: center;
            background-color: var(--white);
        }

        .services h2 {
            font-size: 2.5em;
            margin-bottom: 50px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-item {
            background-color: var(--background-light);
            padding: 40px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .service-item:hover {
            transform: translateY(-10px);
        }

        .service-item h3 {
            margin-bottom: 20px;
            color: var(--secondary-color);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 50px 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
        }

        .footer-section {
            flex: 1;
            padding: 0 15px;
        }

        .footer-section h4 {
            margin-bottom: 20px;
        }

        /* Media Queries */
        @media screen and (max-width: 1024px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
            }

            .hero-text {
                padding-right: 0;
                margin-bottom: 30px;
            }

            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero-text h1 {
                font-size: 2.5em;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                flex-direction: column;
            }

            .footer-section {
                margin-bottom: 30px;
            }
        }
       


        @media screen and (max-width: 480px) {
            .hero-text h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">JN Fast Delivery</div>
            <ul class="nav-menu">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="signup.php">Signup</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </header>
    <section class="content">
    <h2>Our Story</h2>
    <p>Km's Cuisine started with a simple vision: to bring gourmet meals to everyone’s table. With a dedicated team of chefs and delivery experts, we have grown into a trusted name for food lovers. Join us on a journey of flavors and satisfaction!</p>
    <img src="C:\Users\AKEMCHE\Desktop\Akemche\ms website\images\download (1).jpg" alt="Delicious food">
    <p class="description">Experience the best culinary delights crafted with passion and delivered with care. Let us redefine your dining experience with every meal.</p>
</section>

<footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>Email: support@jnfastdelivery.com</p>
                <p>Phone: +237 678 979 492</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <p>Tracking</p>
                <p>Rates</p>
                <p>FAQ</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <p>Facebook</p>
                <p>Twitter</p>
                <p>Instagram</p>
            </div>
        </div>
    </footer>
</body>
</html>
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
            --white: #3498DB;
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
            color: black;
        }
.newsletter form {  
    display: flex;  
    text-align: center;  
    margin-top: 10px;  
}  

.newsletter input[type="email"] {  
    padding: 10px;  
    border: 1px solid #ccc;  
    border-radius: 5px;  
    width: 250px;  
}  

.newsletter button {  
    padding: 10px 15px;  
    border: none;  
    border-radius: 5px;  
    background-color: #28a745;  
    color: white;  
    cursor: pointer;  
    margin-left: 10px;  
}  

.newsletter button:hover {  
    background-color: #218838;  
}  

.faq-item {  
    margin-bottom: 15px; 
    justify-content: center;
    text-align: center; 
}  

.faq-item h3 {  
    color: #2980b9;
  
}  

.blog-list {  
    display: flex;  
    flex-direction: column;  
    gap: 15px;  
}  

.blog-post {  
    background-color: #f9f9f9;  
    padding: 15px;  
    border-radius: 8px;  
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);  
}  

.blog-post h3 {  
    color: #c0392b;  
}  

.blog-post a {  
    display: inline-block;  
    margin-top: 10px;  
    color: #3498db;  
    text-decoration: none;  
}  

.blog-post a:hover {  
    text-decoration: underline;  
}  

        .content {
            background-color: #eaeaea;
            padding: 40px 20px;
            text-align: center;
        }

        .content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.2rem;
            line-height: 1.8;
            max-width: 800px;
            margin: 0 auto 20px;
        }

        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .content .description {
            font-size: 1rem;
            margin-top: 20px;
            color: #555;
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
    </header> 
    <section class="newsletter">
    <h2>Stay Updated!</h2>
    <p>Subscribe to our newsletter for the latest offers and updates.</p>
    <form action="#" method="post">
        <input type="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
    </form>
</section>
<section class="faq">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-item">
        <h3>How do I place an order?</h3>
        <p>Simply browse our menu, add items to your cart, and proceed to checkout.</p>
    </div>
    <div class="faq-item">
        <h3>What are your delivery hours?</h3>
        <p>We deliver from 9 AM to 11 PM every day.</p>
    </div>
</section>

<section class="blog">
    <h2>From Our Blog</h2>
    <div class="blog-list">
        <div class="blog-post">
            <h3>Top 10 Quick Dinner Recipes</h3>
            <p>Discover easy and delicious meals you can cook in no time.</p>
            <a href="#">Read More</a>
    </div>
</section>

</footer>


<script>
    const menuToggle = document.getElementById('menu-toggle');
    const navList = document.getElementById('nav-list');

    menuToggle.addEventListener('click', () => {
        navList.classList.toggle('active');
    });
</script>
</body>
</html>
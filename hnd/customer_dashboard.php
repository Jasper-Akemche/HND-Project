<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8f9fa;
            display: flex;
            min-height: 100vh;
        }
        .dashboard {
            display: flex;
            width: 100%;
        }
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            padding: 25px;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li {
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .sidebar-menu li i {
            margin-right: 12px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }
        .sidebar-menu li:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background-color: white;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .header h1 {
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }
        .content-section {
            display: none;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .content-section.active {
            display: block;
        }
        .delivery-form, .orders-section, .notifications-section {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: #3f51b5;
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
            outline: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background: linear-gradient(135deg, #283593, #1a237e);
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
        }
        .notification-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            transition: all 0.3s;
        }
        .notification-item:hover {
            background-color: #f9f9f9;
            padding-left: 10px;
        }
        .notification-item p {
            font-size: 15px;
            margin-bottom: 5px;
        }
        .notification-item small {
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .section-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .section-header h2 {
            font-size: 22px;
            color: #333;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2><i class="fas fa-user-circle"></i> Customer</h2>
            <ul class="sidebar-menu">
                <li onclick="showSection('request-delivery')"><i class="fas fa-truck"></i> Request Delivery</li>
                <li onclick="showSection('my-orders')"><i class="fas fa-box"></i> My Orders</li>
                <li onclick="showSection('notifications')"><i class="fas fa-bell"></i> Notifications</li>
                <li onclick="location.href='payment.php'"><i class="fas fa-credit-card"></i> Payment</li>
                <li onclick="location.href='login.php'"><i class="fas fa-sign-out-alt"></i> Logout</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-home"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <span id="current-date"></span>
            </div>

            <div id="request-delivery" class="content-section">
                <div class="delivery-form">
                    <div class="section-header">
                        <h2><i class="fas fa-truck"></i> Request a Delivery</h2>
                    </div>
                    <form action="process_request.php" method="POST">
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Pickup Address</label>
                            <input type="text" name="pickup_address" placeholder="Enter pickup location" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-map-pin"></i> Delivery Address</label>
                            <input type="text" name="delivery_address" placeholder="Enter delivery location" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-box-open"></i> Package Type</label>
                            <select name="package_type" required>
                                <option value="">Select Package Type</option>
                                <option value="Small Package">Small Package</option>
                                <option value="Medium Package">Medium Package</option>
                                <option value="Large Package">Large Package</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-sticky-note"></i> Additional Notes</label>
                            <textarea name="additional_notes" rows="4" placeholder="Special instructions or information"></textarea>
                        </div>
                        <button class="btn" type="submit"><i class="fas fa-paper-plane"></i> Submit Delivery Request</button>
                    </form>
                </div>
            </div>

            <div id="my-orders" class="content-section">
                <div class="orders-section">
                    <div class="section-header">
                        <h2><i class="fas fa-box"></i> My Orders</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PHP would populate this dynamically -->
                            <tr>
                                <td>ORD-001</td>
                                <td>2024-03-25</td>
                                <td><span style="background-color: #e3f2fd; padding: 5px 10px; border-radius: 20px; color: #1565c0; font-size: 14px;"><i class="fas fa-truck"></i> In Transit</span></td>
                                <td><button class="btn" style="padding: 8px 15px; font-size: 14px;">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="notifications" class="content-section">
                <div class="notifications-section">
                    <div class="section-header">
                        <h2><i class="fas fa-bell"></i> Notifications</h2>
                    </div>
                    <div class="notification-item">
                        <p><i class="fas fa-truck"></i> Your order ORD-001 is out for delivery</p>
                        <small><i class="fas fa-calendar-alt"></i> March 25, 2024</small>
                    </div>
                    <div class="notification-item">
                        <p><i class="fas fa-user"></i> Delivery partner assigned to your package</p>
                        <small><i class="fas fa-calendar-alt"></i> March 24, 2024</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
        }
        
        // Show request delivery section by default
        showSection('request-delivery');
        
        // Display current date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').innerHTML = new Date().toLocaleDateString('en-US', options);
    </script>
</body>
</html>
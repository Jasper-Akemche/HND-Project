
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }
        .dashboard {
            display: flex;
            width: 100%;
        }
        .sidebar {
            width: 250px;
            background-color: midnightblue;
            color: white;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            font-weight: bold;
            font-size: 24px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li {
            padding: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .sidebar-menu li i {
            margin-right: 10px;
            width: 24px;
            text-align: center;
        }
        .sidebar-menu li:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar-menu li.active {
            background-color: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #ecf0f1;
            transition: all 0.3s ease;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-right {
            display: flex;
            align-items: center;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: midnightblue;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .notification-bell {
            margin-left: 20px;
            position: relative;
            cursor: pointer;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            font-size: 12px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .content-section {
            display: none;
        }
        .content-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .customers-icon {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        .drivers-icon {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }
        .deliveries-icon {
            background-color: rgba(230, 126, 34, 0.1);
            color: #e67e22;
        }
        .revenue-icon {
            background-color: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
        }
        .card-title {
            font-size: 14px;
            color: #7f8c8d;
        }
        .card-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .card-footer {
            font-size: 14px;
            color: #7f8c8d;
        }
        .card-footer .positive {
            color: #2ecc71;
        }
        .card-footer .negative {
            color: #e74c3c;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .data-table th, .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f4f4f4;
        }
        .data-table th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #2c3e50;
        }
        .data-table tr:hover {
            background-color: #f9f9f9;
        }
        .data-table .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .data-table .status-active {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }
        .data-table .status-inactive {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        .data-table .status-pending {
            background-color: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: midnightblue;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: darkblue;
        }
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }
        .btn-edit {
            background-color: #3498db;
        }
        .btn-delete {
            background-color: #e74c3c;
        }
        .btn-view {
            background-color: #2ecc71;
        }
        .search-bar {
            display: flex;
            margin-bottom: 20px;
        }
        .search-bar input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: midnightblue;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: midnightblue;
            outline: none;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .tab-navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-button {
            padding: 10px 20px;
            background-color: #f4f4f4;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .tab-button.active {
            background-color: midnightblue;
            color: white;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }
        .pagination-item {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            cursor: pointer;
            background-color: #f4f4f4;
            transition: all 0.3s;
        }
        .pagination-item.active {
            background-color: midnightblue;
            color: white;
        }
        .pagination-item:hover:not(.active) {
            background-color: #ddd;
        }
        .chart-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .notification-item {
            border-bottom: 1px solid #eee;
            padding: 15px;
            transition: background-color 0.3s;
        }
        .notification-item:hover {
            background-color: #f9f9f9;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .notification-title {
            font-weight: bold;
        }
        .notification-time {
            color: #7f8c8d;
            font-size: 12px;
        }
        .notification-content {
            color: #34495e;
        }
        .notification-unread {
            position: relative;
        }
        .notification-unread::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #3498db;
        }
        #admin-overview {
            margin-bottom: 30px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 5px;
            width: 50%;
            max-width: 700px;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .modal-title {
            font-size: 20px;
            font-weight: bold;
        }
        .close {
            font-size: 24px;
            cursor: pointer;
        }
        .close:hover {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2><i class="fas fa-tachometer-alt"></i> ADMIN</h2>
            <ul class="sidebar-menu">
                <li onclick="showSection('admin-dashboard')" class="active"><i class="fas fa-home"></i> Dashboard</li>
                <li onclick="location.href='manage_users.php'"><i class="fas fa-users"></i> Manage Customers</li>
                <li onclick="location.href='manage_users.php'"><i class="fas fa-truck"></i> Manage Drivers</li>
                <li onclick="showSection('delivery-requests')"><i class="fas fa-box"></i> Delivery Requests</li>
                <li onclick="showSection('delivery-tracking')"><i class="fas fa-map-marker-alt"></i> Delivery Tracking</li>
                <li onclick="showSection('reports')"><i class="fas fa-chart-bar"></i> Reports</li>
                <li onclick="showSection('notifications')"><i class="fas fa-bell"></i> Notifications</li>
                <li onclick="showSection('settings')"><i class="fas fa-cog"></i> Settings</li>
                <li onclick="location.href='login.php'"><i class="fas fa-sign-out-alt"></i> Logout</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <div>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                            <small>Administrator</small>
                        </div>
                    </div>
                    <div class="notification-bell" onclick="showSection('notifications')">
                        <i class="fas fa-bell fa-lg"></i>
                        <div class="notification-badge">3</div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Overview Section -->
            <div id="admin-dashboard" class="content-section active">
                <h2 style="margin-bottom: 20px;">Overview</h2>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">TOTAL CUSTOMERS</div>
                                <div class="card-value">2,547</div>
                            </div>
                            <div class="card-icon customers-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="positive">+12%</span> since last month
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">ACTIVE DRIVERS</div>
                                <div class="card-value">124</div>
                            </div>
                            <div class="card-icon drivers-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="positive">+5%</span> since last month
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">TODAY'S DELIVERIES</div>
                                <div class="card-value">85</div>
                            </div>
                            <div class="card-icon deliveries-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="positive">+18%</span> since yesterday
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">PENDING REQUESTS</div>
                                <div class="card-value">32</div>
                            </div>
                            <div class="card-icon revenue-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="negative">+7%</span> since yesterday
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <h3 style="margin-bottom: 15px;">Recent Activity</h3>
                    <div class="tab-navigation">
                        <button class="tab-button active">All Activities</button>
                        <button class="tab-button">Customers</button>
                        <button class="tab-button">Drivers</button>
                        <button class="tab-button">Deliveries</button>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>New delivery request</td>
                                <td>John Smith</td>
                                <td>Customer</td>
                                <td>10 minutes ago</td>
                                <td><span class="status status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Delivery completed</td>
                                <td>Mike Johnson</td>
                                <td>Driver</td>
                                <td>25 minutes ago</td>
                                <td><span class="status status-active">Completed</span></td>
                            </tr>
                            <tr>
                                <td>Customer registration</td>
                                <td>Emily Davis</td>
                                <td>Customer</td>
                                <td>1 hour ago</td>
                                <td><span class="status status-active">Active</span></td>
                            </tr>
                            <tr>
                                <td>Driver status update</td>
                                <td>Robert Wilson</td>
                                <td>Driver</td>
                                <td>2 hours ago</td>
                                <td><span class="status status-inactive">Inactive</span></td>
                            </tr>
                            <tr>
                                <td>Delivery assigned</td>
                                <td>Sarah Thompson</td>
                                <td>Admin</td>
                                <td>3 hours ago</td>
                                <td><span class="status status-active">Assigned</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <div class="pagination-item"><i class="fas fa-chevron-left"></i></div>
                        <div class="pagination-item active">1</div>
                        <div class="pagination-item">2</div>
                        <div class="pagination-item">3</div>
                        <div class="pagination-item">...</div>
                        <div class="pagination-item">10</div>
                        <div class="pagination-item"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Manage Customers Section -->
            <div id="manage-customers" class="content-section">
                <h2 style="margin-bottom: 20px;">Manage Customers</h2>
                
                <div class="search-bar">
                    <input type="text" placeholder="Search customers...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div class="tab-navigation">
                        <button class="tab-button active">All Customers</button>
                        <button class="tab-button">Active</button>
                        <button class="tab-button">Inactive</button>
                    </div>
                    <button class="btn" onclick="openModal('add-customer-modal')"><i class="fas fa-plus"></i> Add Customer</button>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>C-001</td>
                            <td>John Smith</td>
                            <td>john.smith@example.com</td>
                            <td>+1 234-567-8901</td>
                            <td>2024-02-15</td>
                            <td><span class="status status-active">Active</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-small btn-view" onclick="openModal('view-customer-modal')"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-small btn-edit" onclick="openModal('edit-customer-modal')"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-small btn-delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>C-002</td>
                            <td>Emily Davis</td>
                            <td>emily.davis@example.com</td>
                            <td>+1 987-654-3210</td>
                            <td>2024-03-01</td>
                            <td><span class="status status-active">Active</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-small btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-small btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-small btn-delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>C-003</td>
                            <td>Michael Johnson</td>
                            <td>michael.j@example.com</td>
                            <td>+1 555-123-4567</td>
                            <td>2024-02-10</td>
                            <td><span class="status status-inactive">Inactive</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-small btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-small btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-small btn-delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>C-004</td>
                            <td>Sarah Wilson</td>
                            <td>sarah.w@example.com</td>
                            <td>+1 321-654-0987</td>
                            <td>2024-03-10</td>
                            <td><span class="status status-active">Active</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-small btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-small btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-small btn-delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>C-005</td>
                            <td>Robert Brown</td>
                            <td>robert.b@example.com</td>
                            <td>+1 789-456-1230</td>
                            <td>2024-03-15</td>
                            <td><span class="status status-active">Active</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-small btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-small btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-small btn-delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <div class="pagination-item"><i class="fas fa-chevron-left"></i></div>
                    <div class="pagination-item active">1</div>
                    <div class="pagination-item">2</div>
                    <div class="pagination-item">3</div>
                    <div class="pagination-item">...</div>
                    <div class="pagination-item">10</div>
                    <div class="pagination-item"><i class="fas fa-chevron-right"></i></div>
                </div>
            </div>

            <!-- Manage Drivers Section -->
            <div id="manage-drivers" class="content-section">
                <h2 style="margin-bottom: 20px;">Manage Drivers</h2>
                
                <div class="search-bar">
                    <input type="text" placeholder="Search drivers...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div class="tab-navigation">
                        <button class="tab-button active">All Drivers</button>
                        <button class="tab-button">Available</button>
                        <button class="tab-button">On Delivery</button>
                        <button class="tab-button">Offline</button>
                    </div>
                    <button class="btn" onclick="openModal('add-driver-modal')"><i class="fas fa-plus"></i> Add Driver</button>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Vehicle Type</th>
                            <th>License #</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>D-001</td>
                            <td>James Wilson</td>
                            <td>+1 456-789-0123</td>
                            <td>Motorcycle</td>
                            <td>MC12345</td>
                            <td><span class="status status-active">Available</span></td>
                            <td>4.8 ★</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-small btn-view" onclick="openModal('view-driver-modal')"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-small btn-edit" onclick="openModal('edit-driver-modal')"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-small btn-delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>D-002</td>
                            <td>Maria Garcia</td>
                            <td>+1 789-012-3456</td>
                            <td>Car</td>
                            <td>CA54321</td>
                            <td><span class="status status-pending">On Delivery</span></td>
                            <td>4.5 ★</td>




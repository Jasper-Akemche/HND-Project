<?php
// Start session at the beginning
session_start();

// Include the configuration file
require_once 'db_connection.php';

// Fetch requests from the table 'requests' (aliased as t)
try {
    $sql = "SELECT 
                t.request_id, 
                t.pickup_address,
                t.delivery_address,
                t.package_type,
                t.additional_notes,
                t.request_date,
                t.status 
            FROM requests t 
            WHERE t.status = 'pending'
            ORDER BY t.request_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Query Error: " . $e->getMessage());
    $requests = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivers Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/phosphor-icons"></script>
    <style>
        /* Base styling improvements */
        body {
            font-family: 'Inter', 'Segoe UI', 'Roboto', sans-serif;
            background-color: #f3f4f6;
        }

        /* Midnight blue sidebar */
        .midnight-sidebar {
            background-color: #191970; /* Midnight blue */
        }

        /* Improved sidebar styling */
        .sidebar-link {
            transition: all 0.2s ease-in-out;
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        /* Updated hover color for sidebar links to match midnight blue */
        .midnight-hover:hover {
            background-color: #2a2a8c; /* Slightly lighter midnight blue */
        }

        /* Table styling enhancements */
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        table th {
            background-color: #191970; /* Match sidebar */
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 12px 16px;
        }

        table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table tr:hover {
            background-color: #eff6ff;
        }

        /* Button styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 0.375rem;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-accept {
            background-color:midnightblue;
            color: white;
        }

        .btn-accept:hover {
            background-color: darkblue;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-decline {
            background-color:darkblue;
            color: white;
        }

        .btn-decline:hover {
            background-color:midnightblue;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Card styling */
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.07);
        }

        /* Map container styling */
        .map-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        /* Section heading styling */
        .section-heading {
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #191970; /* Match sidebar */
        }

        .section-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background-color: #191970; /* Match sidebar */
            border-radius: 2px;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Animation for page transitions */
        .section {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: fixed;
                bottom: 0;
                left: 0;
                height: auto;
                z-index: 10;
            }
            
            .main-content {
                margin-left: 0;
                padding-bottom: 80px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Fix for the hidden class issue */
        .hidden {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar Navigation with midnight blue color -->
        <div class="w-64 text-white p-6 flex flex-col sidebar midnight-sidebar">
            <div class="mb-10">
                <h1 class="text-2xl text-black text-center font-bold mb-6">Drivers</h1>
                
                <nav>
                    <ul class="space-y-4">
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg transition duration-300 sidebar-link midnight-hover" onclick="showSection('deliveries')">
                                <i class="ph-list-checks mr-3 text-xl"></i>
                                Available Requests
                            </a>
                        </li>
                        <li>
                            <a href="my_requests.php" class="flex items-center p-3 rounded-lg transition duration-300 sidebar-link midnight-hover">
                                <i class="ph-truck mr-3 text-xl"></i>
                                My Requests
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg transition duration-300 sidebar-link midnight-hover" onclick="logout()">
                                <i class="ph-sign-out mr-3 text-xl"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <div class="mt-auto text-sm text-green-200">
                <p>&copy; 2024 Drivers</p>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 p-8 overflow-y-auto main-content">
            <!-- Requests Section -->
            <div id="deliveries" class="section">
                <h2 class="text-3xl font-bold mb-6 text-gray-800 section-heading">Available Requests</h2>
                <div class="bg-white shadow-lg rounded-lg p-6 card">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Pickup Address</th>
                                    <th>Delivery Address</th>
                                    <th>Package Type</th>
                                    <th>Additional Notes</th>
                                    <th>Request Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($requests)): ?>
                                    <?php foreach($requests as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['request_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['pickup_address']); ?></td>
                                        <td><?php echo htmlspecialchars($row['delivery_address']); ?></td>
                                        <td><?php echo htmlspecialchars($row['package_type']); ?></td>
                                        <td><?php echo htmlspecialchars($row['additional_notes']); ?></td>
                                        <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                                        <td class="space-x-2">
                                            <a href="process_accept.php?request_id=<?php echo urlencode($row['request_id']); ?>" class="btn btn-accept">
                                                Accept
                                            </a>
                                            <a href="process_decline.php?request_id=<?php echo urlencode($row['request_id']); ?>" class="btn btn-decline">
                                                Decline
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center p-4">No requests found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Google Map Section -->
            <div class="mt-8 bg-white shadow-lg rounded-lg p-6 card">
                <h2 class="text-3xl font-bold mb-6 text-gray-800 section-heading">Yaoundé City</h2>
                <div class="w-full h-96 map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.083419847145!2d11.509517914754172!3d3.848052299999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x108bcf1df8b37df1%3A0x92dcd53d86b96a5e!2sYaound%C3%A9!5e0!3m2!1sen!2scm!4v1600000000000!5m2!1sen!2scm" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            // Show selected section
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function logout() {
            // Redirect to login page
            window.location.href = 'login.php';
        }

        // Automatically show requests section on load
        document.addEventListener('DOMContentLoaded', () => {
            showSection('deliveries');
        });
    </script>
</body>
</html>
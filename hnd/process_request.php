<?php
session_start();
require_once 'db_connection.php';

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the user is authenticated
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// If user_id is not set in session, try to retrieve it from the database using user_name
if (!isset($_SESSION['user_id'])) {
    $username = $_SESSION['user_name'];
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
        } else {
            // If no user is found, redirect to login
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error retrieving user information: " . $e->getMessage());
    }
}

// Retrieve and sanitize form data
$pickup_address   = trim($_POST['pickup_address']);
$delivery_address = trim($_POST['delivery_address']);
$package_type     = trim($_POST['package_type']);
$additional_notes = isset($_POST['additional_notes']) ? trim($_POST['additional_notes']) : '';

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

try {
    // Prepare SQL statement to insert a new delivery request
    $sql = "INSERT INTO requests (user_id, pickup_address, delivery_address, package_type, additional_notes) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $pickup_address, $delivery_address, $package_type, $additional_notes]);

    // Set a success message and redirect back to the dashboard
    $_SESSION['request_success'] = "Your delivery request has been submitted.";
    header("Location: customer_dashboard.php");
    exit();
} catch (PDOException $e) {
    // Log the error and redirect with an error message
    error_log("Request Submission Error: " . $e->getMessage());
    $_SESSION['request_error'] = "There was an error submitting your request. Please try again.";
    header("Location: customer_dashboard.php");
    exit();
}
?>

<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['request_id'])) {
    die("No request ID provided.");
}

$request_id = $_GET['request_id'];
$driver_id = $_SESSION['driver_id']; // Assuming you have driver ID in session

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Update request status to 'accepted'
    $sql = "UPDATE requests 
            SET status = 'accepted', 
                driver_id = :driver_id 
            WHERE request_id = :request_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':driver_id' => $driver_id,
        ':request_id' => $request_id
    ]);

    // Insert notification for user
    $notify_sql = "INSERT INTO notifications 
                   (user_id, message, request_id, type) 
                   SELECT user_id, 
                          'Your delivery request has been accepted by a driver.', 
                          :request_id, 
                          'acceptance'
                   FROM requests 
                   WHERE request_id = :request_id";
    $notify_stmt = $pdo->prepare($notify_sql);
    $notify_stmt->execute([':request_id' => $request_id]);

    // Commit transaction
    $pdo->commit();

    // Redirect to my_requests.php
    header("Location: my_requests.php");
    exit();

} catch (PDOException $e) {
    // Rollback transaction in case of error
    $pdo->rollBack();
    error_log("Error processing request: " . $e->getMessage());
    die("An error occurred while processing your request.");
}
?>

<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['request_id'])) {
    die("No request ID provided.");
}

$request_id = $_GET['request_id'];

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Update request status to 'declined'
    $sql = "UPDATE requests 
            SET status = 'declined' 
            WHERE request_id = :request_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':request_id' => $request_id]);

    // Insert notification for user
    $notify_sql = "INSERT INTO notifications 
                   (user_id, message, request_id, type) 
                   SELECT user_id, 
                          'Your delivery request has been declined by a driver.', 
                          :request_id, 
                          'decline'
                   FROM requests 
                   WHERE request_id = :request_id";
    $notify_stmt = $pdo->prepare($notify_sql);
    $notify_stmt->execute([':request_id' => $request_id]);

    // Commit transaction
    $pdo->commit();

    // Redirect back to dashboard
    header("Location: drivers_dashboard.php");
    exit();

} catch (PDOException $e) {
    // Rollback transaction in case of error
    $pdo->rollBack();
    error_log("Error processing request: " . $e->getMessage());
    die("An error occurred while processing your request.");
}
?>

<?php
session_start();
require_once 'db_connection.php';

// Fetch driver's accepted requests
try {
    $sql = "SELECT 
                request_id, 
                pickup_address,
                delivery_address,
                package_type,
                additional_notes,
                request_date,
                status 
            FROM requests 
            WHERE driver_id = :driver_id 
            AND status = 'accepted'
            ORDER BY request_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':driver_id' => $_SESSION['driver_id']]);
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
    <title>My Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/phosphor-icons"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">My Accepted Requests</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-6">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Request ID</th>
                        <th class="p-3">Pickup Address</th>
                        <th class="p-3">Delivery Address</th>
                        <th class="p-3">Package Type</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Request Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach($requests as $row): ?>
                        <tr class="border-b">
                            <td class="p-3"><?php echo htmlspecialchars($row['request_id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['pickup_address']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['delivery_address']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['package_type']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['request_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center p-4">No accepted requests found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
session_start();
require_once 'db_connection.php'; // Make sure this file is in the same directory

// Authentication function using the password_hash from the database
function authenticateUser($username, $password, $role) {
    global $pdo;
    try {
        // Query to get the hashed password for the user with a given role
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ? AND role = ?");
        $stmt->execute([$username, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify the provided password against the stored hash
        if ($user && password_verify($password, $user['password_hash'])) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (authenticateUser($username, $password, $role)) {
        // Set session variables consistently (use 'user_name')
        $_SESSION['user_name'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role, for example: customer_dashboard.php
        header("Location: " . $role . "_dashboard.php");
        exit();
    } else {
        $error = "Invalid username, password, or role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Dispatch - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .signup-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($error)) {
            echo "<div class='error-message'>$error</div>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Select Role</label>
                <select id="role" name="role" required>
                    <option value="">Select a Role</option>
                    <option value="customer">Customer</option>
                    <option value="driver">Driver</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </div>
</body>
</html>

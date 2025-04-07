<?php
session_start();
require_once 'db_connection.php'; // Include the database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic user information
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);
    
    // Additional personal information
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $telephone = trim($_POST['telephone']);
    
    // Driver-specific information (if applicable)
    $license_number = ($role === 'driver' && isset($_POST['license_number'])) ? trim($_POST['license_number']) : null;

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "Username already exists. Please choose another.";
        }

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already exists. Please choose another.";
        }

        // If no errors, insert the user into the database
        if (!isset($error)) {
            // Hash the password before storing it
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Handle profile picture upload
            $profile_picture_path = 'images/default-avatar.png'; // Default image path
            
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/profile_pictures/';
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Generate a unique filename
                $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('profile_') . '.' . $file_extension;
                $target_file = $upload_dir . $filename;
                
                // Check if the file is an actual image
                $check = getimagesize($_FILES['profile_picture']['tmp_name']);
                if ($check !== false) {
                    // Attempt to upload the file
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                        $profile_picture_path = $target_file;
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $error = "File is not an image.";
                }
            }

            // If still no errors after file upload check
            if (!isset($error)) {
                try {
                    // Start transaction
                    $pdo->beginTransaction();
                    
                    // Insert the new user into the users table with additional fields
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, first_name, last_name, 
                                          telephone, profile_picture_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $username, 
                        $email, 
                        $password_hash, 
                        $role, 
                        $first_name, 
                        $last_name, 
                        $telephone, 
                        $profile_picture_path
                    ]);
                    
                    $user_id = $pdo->lastInsertId();
                    
                    // If user is a driver, store driver-specific information
                    if ($role === 'driver' && $license_number) {
                        $stmt = $pdo->prepare("INSERT INTO driver_details (user_id, license_number) VALUES (?, ?)");
                        $stmt->execute([$user_id, $license_number]);
                    }
                    
                    // Commit transaction
                    $pdo->commit();

                    // Redirect user to login page after successful registration
                    header("Location: login.php?success=1");
                    exit();
                } catch (PDOException $e) {
                    // Rollback transaction on error
                    $pdo->rollBack();
                    $error = "Database error: " . $e->getMessage();
                }
            }
        }
    }
    
    // If there was an error, include the signup page to display the error
    include('signup.php');
    exit();
}
else {
    // If the script is accessed directly without POST, redirect to signup page
    header("Location: signup.php");
    exit();
}
?>
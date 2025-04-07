<?php session_start(); require_once 'db_connection.php'; // Include the database connection ?>  <!DOCTYPE html> <html lang="en"> <head>     <meta charset="UTF-8">     <meta name="viewport" content="width=device-width, initial-scale=1.0">     <title>JN Fast Delivery - Sign Up</title>     <style>         body {             font-family: Arial, sans-serif;             background-color: #f4f4f4;             display: flex;             justify-content: center;             align-items: center;             height: 100vh;             margin: 0;         }          .signup-container {             background-color: white;             padding: 40px;             border-radius: 10px;             box-shadow: 0 4px 6px rgba(0,0,0,0.1);             width: 100%;             max-width: 400px;
             overflow-y: auto;
             max-height: 90vh;
         }          .signup-container h2 {             text-align: center;             color: #333;             margin-bottom: 20px;         }          .form-group {             margin-bottom: 15px;         }          .form-group label {             display: block;             margin-bottom: 5px;         }          .form-group input,          .form-group select {             width: 100%;             padding: 10px;             border: 1px solid #ddd;             border-radius: 5px;         }          .form-group button {             width: 100%;             padding: 10px;             background-color: #3498db;             color: white;             border: none;             border-radius: 5px;             cursor: pointer;         }          .form-group button:hover {             background-color: #2980b9;         }          .error-message {             color: red;             text-align: center;             margin-bottom: 15px;         }          .login-link {             text-align: center;             margin-top: 15px;         }
         
         .profile-upload {
             display: flex;
             flex-direction: column;
             align-items: center;
             margin-bottom: 20px;
         }
         
         .profile-upload img {
             width: 100px;
             height: 100px;
             border-radius: 50%;
             object-fit: cover;
             margin-bottom: 10px;
             border: 2px solid #ddd;
         }
         
         .driver-fields {
             display: none;
             border-top: 1px solid #eee;
             padding-top: 15px;
             margin-top: 15px;
         }
     </style> </head> <body>     <div class="signup-container">         <h2>Sign Up</h2>         <?php         if (isset($error)) {             echo "<div class='error-message'>$error</div>";         }         ?>         <form action="process_signup.php" method="POST" enctype="multipart/form-data">
             <div class="profile-upload">
                 <img id="profile-preview" src="images/default-avatar.png" alt="Profile Preview">
                 <div class="form-group">
                     <label for="profile_picture">Profile Picture</label>
                     <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)">
                 </div>
             </div>
             
             <div class="form-group">
                 <label for="first_name">First Name</label>
                 <input type="text" id="first_name" name="first_name" required>
             </div>
             
             <div class="form-group">
                 <label for="last_name">Last Name</label>
                 <input type="text" id="last_name" name="last_name" required>
             </div>
             
             <div class="form-group">
                 <label for="telephone">Telephone Number</label>
                 <input type="tel" id="telephone" name="telephone" required>
             </div>
             
             <div class="form-group">                 <label for="username">Username</label>                 <input type="text" id="username" name="username" required>             </div>             <div class="form-group">                 <label for="email">Email</label>                 <input type="email" id="email" name="email" required>             </div>             <div class="form-group">                 <label for="password">Password</label>                 <input type="password" id="password" name="password" required>             </div>             <div class="form-group">                 <label for="confirm_password">Confirm Password</label>                 <input type="password" id="confirm_password" name="confirm_password" required>             </div>             <div class="form-group">                 <label for="role">Select Role</label>                 <select id="role" name="role" required onchange="toggleDriverFields()">                     <option value="">Select a Role</option>                     <option value="customer">Customer</option>                     <option value="driver">Driver</option>                     <option value="admin">Admin</option>                 </select>             </div>
             
             <div id="driver-fields" class="driver-fields">
                 <div class="form-group">
                     <label for="license_number">Driver's License Number</label>
                     <input type="text" id="license_number" name="license_number">
                 </div>
             </div>
             
             <div class="form-group">                 <button type="submit">Sign Up</button>             </div>         </form>         <div class="login-link">             Already have an account? <a href="login.php">Login</a>         </div>     </div>
     
     <script>
         function previewImage(input) {
             if (input.files && input.files[0]) {
                 var reader = new FileReader();
                 
                 reader.onload = function(e) {
                     document.getElementById('profile-preview').src = e.target.result;
                 }
                 
                 reader.readAsDataURL(input.files[0]);
             }
         }
         
         function toggleDriverFields() {
             var role = document.getElementById('role').value;
             var driverFields = document.getElementById('driver-fields');
             
             if (role === 'driver') {
                 driverFields.style.display = 'block';
                 document.getElementById('license_number').required = true;
             } else {
                 driverFields.style.display = 'none';
                 document.getElementById('license_number').required = false;
             }
         }
     </script>
</body> </html>
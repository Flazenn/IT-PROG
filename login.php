<?php
session_start();
require 'dbconnection.php';

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: " . $_SESSION['role'] . ".php");
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = mysqli_query($con,
            "SELECT * FROM users WHERE username = '$username'");

        if ($row = mysqli_fetch_assoc($result)){
            if($row['password'] == $password) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                
                // Redirect to role-based dashboard
                header("Location: " . $_SESSION['role'] . ".php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>APS Login</title>
    <style>
        body { font-family: Arial; margin: 50px; background: #f0f0f0; }
        .login-form { width: 300px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
        .user-list { margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>APS Login</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="action" value="login">Login</button>
        </form>
        <div class="user-list">
            <strong>Accounts For Testing(remove later):</strong><br>
            employee: john / qwerty1234!<br>
            HR: jane / pass123<br>
            Finance: mike / pass123<br>
            Admin: admin / admin123
        </div>
    </div>
</body>
</html>

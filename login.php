<?php
session_start();

// Hardcoded users (no database needed)
$users = [
    'john_employee' => [
        'password' => 'pass123',
        'role' => 'employee',
        'name' => 'John Doe',
        'email' => 'john@email.com'
    ],
    'jane_hr' => [
        'password' => 'pass123',
        'role' => 'hr',
        'name' => 'Jane Smith',
        'email' => 'jane@email.com'
    ],
    'mike_finance' => [
        'password' => 'pass123',
        'role' => 'finance',
        'name' => 'Mike Wilson',
        'email' => 'mike@email.com'
    ],
    'admin' => [
        'password' => 'admin123',
        'role' => 'admin',
        'name' => 'Admin User',
        'email' => 'admin@email.com'
    ]
];

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: " . $_SESSION['role'] . "/");
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if(isset($users[$username]) && $users[$username]['password'] == $password) {
        $_SESSION['user_id'] = $username;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'];
        $_SESSION['name'] = $users[$username]['name'];
        $_SESSION['email'] = $users[$username]['email'];
        
        // Redirect to role-based dashboard
        header("Location: " . $users[$username]['role'] . "/");
        exit();
    } else {
        $error = "Invalid username or password";
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
            <button type="submit">Login</button>
        </form>
        <div class="user-list">
            <strong>Test Accounts:</strong><br>
            employee: john_employee / pass123<br>
            HR: jane_hr / pass123<br>
            Finance: mike_finance / pass123<br>
            Admin: admin / admin123
        </div>
    </div>
</body>
</html>

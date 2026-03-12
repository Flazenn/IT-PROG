<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; }
        .container { display: flex; }
        .sidebar { width: 250px; background: #34495e; min-height: calc(100vh - 60px); }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 12px 20px; }
        .sidebar a:hover { background: #2c3e50; }
        .sidebar a.active { background: #e74c3c; }
        .content { flex: 1; padding: 20px; }
        .card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="header">
        <h2>APS - Admin Panel</h2>
        <p>Welcome, <?php echo $_SESSION['name']; ?></p>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="tickets.php">Tickets</a>
            <a href="users.php">Users</a>
            <a href="adduser.php">Add User</a>
            <a href="removeuser.php">Remove User</a>
            <a href="edituserdata.php">Edit User Data</a>
            <a href="../logout.php">Logout</a>
        </div>
        <div class="content">
            <div class="card">
                <h3>Admin Dashboard</h3>
                <p>System overview and management tools.</p>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Finance Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; }
        .container { display: flex; }
        .sidebar { width: 250px; background: #34495e; min-height: calc(100vh - 60px); }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 12px 20px; }
        .sidebar a:hover { background: #2c3e50; }
        .sidebar a.active { background: #e67e22; }
        .content { flex: 1; padding: 20px; }
        .card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="header">
        <h2>APS - Finance Department</h2>
        <p>Welcome, <?php echo $_SESSION['name']; ?></p>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="index.php" class="active">Dashboard</a>
            <a href="compute_salary.php">Compute Salary</a>
            <a href="approve_salary.php">Approve Salaries</a>
            <a href="ticket_request.php">Request Ticket</a>
            <a href="send_payslip.php">Send Payslips</a>
            <a href="../logout.php">Logout</a>
        </div>
        <div class="content">
            <div class="card">
                <h3>Finance Dashboard</h3>
                <p>This week's salary computations pending: 15</p>
            </div>
        </div>
    </div>
</body>
</html>
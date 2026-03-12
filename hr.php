<!DOCTYPE html>
<html>
<head>
    <title>HR Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .container { display: flex; min-height: calc(100vh - 60px); }
        .sidebar { width: 250px; background: #34495e; color: white; padding: 20px 0; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 12px 20px; }
        .sidebar a:hover { background: #2c3e50; }
        .sidebar a.active { background: #27ae60; }
        .content { flex: 1; padding: 20px; }
        .card { background: white; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-box { background: white; padding: 20px; border-radius: 5px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-box h4 { color: #666; font-size: 14px; }
        .stat-box .number { font-size: 24px; font-weight: bold; color: #27ae60; }
    </style>
</head>
<body>
    <div class="header">
        <h2>APS - Automated Payroll System</h2>
        <div>Welcome, <?php echo $_SESSION['name']; ?> (HR)</div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="index.php" class="active">Dashboard</a>
            <a href="employees.php">Employees</a>
            <a href="salary_data.php">Salary Data</a>
            <a href="time_adjustment.php">Time Adjustments</a>
            <a href="ticket_request.php">Request Ticket</a>
            <a href="send_finance.php">Send to Finance</a>
            <a href="../logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>
        </div>
        
        <div class="content">
            <div class="stats">
                <div class="stat-box">
                    <h4>Total Employees</h4>
                    <div class="number">24</div>
                </div>
                <div class="stat-box">
                    <h4>Time Adjustments</h4>
                    <div class="number">3</div>
                </div>
                <div class="stat-box">
                    <h4>Pending Tickets</h4>
                    <div class="number">2</div>
                </div>
            </div>
            
            <div class="card">
                <h3>HR Dashboard</h3>
                <p>Welcome to HR panel. You can manage employee records and time adjustments.</p>
            </div>
            
            <div class="card">
                <h3>Recent Activities</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background: #f4f4f4;">
                        <th style="padding: 10px; text-align: left;">Employee</th>
                        <th style="padding: 10px; text-align: left;">Action</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">John Doe</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">Time Adjustment</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">Pending</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">Jane Smith</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">Salary Update</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">Approved</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
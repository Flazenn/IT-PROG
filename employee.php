<?php
session_start();
date_default_timezone_set('Asia/Manila');

 require 'dbconnection.php';

 if (!isset($_SESSION['user_id'])) {

     header("Location: login.php");

     exit();

 }
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($con, "SELECT * FROM employee_data WHERE user_id='$user_id'");
    $row = mysqli_fetch_assoc($result);

    $employee_id = $row['employee_id'];
    $today = date('Y-m-d');

    $check = mysqli_query($con, "SELECT * FROM attendance 
                                WHERE employee_id='$employee_id' 
                                AND DATE(time_in) = '$today'");

    $attendance= mysqli_fetch_assoc($check);

    if (!$attendance){
        $status = 'not_clocked_in';
    } else if ($attendance['time_out'] == NULL){
        $status = 'clocked_in';
    } else {
        $status = 'clocked_out';
    }
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h2 { margin: 0; font-size: 20px; }
        .user-info { font-size: 14px; }
        .container { display: flex; min-height: calc(100vh - 60px); }
        .sidebar { width: 250px; background: #34495e; color: white; padding: 20px 0; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 12px 20px; transition: background 0.3s; }
        .sidebar a:hover { background: #2c3e50; }
        .sidebar a.active { background: #3498db; }
        .content { flex: 1; padding: 20px; }
        .card { background: white; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 15px; color: #333; }
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
        .action-btn { background: #3498db; color: white; text-decoration: none; padding: 15px; text-align: center; border-radius: 5px; transition: background 0.3s; }
        .action-btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="header">
        <h2>APS - Automated Payroll System</h2>
        <div class="user-info">
            Welcome, <?php echo $_SESSION['username']; ?> (<?php echo ucfirst($_SESSION['role']); ?>)
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="index.php" class="active">Dashboard</a>
            <a href="payslip.php">My Payslip</a>
            <a href="timesheet.php">My Timesheet</a>
            <a href="timein.php">Time In</a>
            <a href="timeout.php">Time Out</a>
            <a href="logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>
        </div>
        
        <div class="content">
            <div class="card">
                <h3>Employee Dashboard</h3>
                <p>Welcome to your employee dashboard. Here you can view your payslips and record your attendance.</p>
            </div>
            
            <div class="card">
                <h3>Today's Status</h3>
                <p>Date: <?php echo date('F j, Y'); ?></p>
                <p>Time: <?php echo date('h:i A'); ?></p>

                <?php
                    if ($status == 'not_clocked_in'){
                        echo "Status: <span style='color: gray; font-weight: bold;'>Not Clocked In</span></p>";
                    }else if ($status == 'clocked_out'){
                        echo "Status: <span style='color: red; font-weight: bold;'>Clocked Out</span></p>"; 
                    }else {
                        echo "Status: <span style='color: green; font-weight: bold;'>Clocked In</span></p>";
                    }
                ?>

            </div>
            
            </div>
        </div>
    </div>
</body>
</html>

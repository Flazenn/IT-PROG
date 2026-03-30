<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
    require 'dbconnection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $emp_result = mysqli_query($con, "SELECT * 
                                      FROM employee_data 
                                      WHERE user_id= '$user_id'");
    $emp = mysqli_fetch_assoc($emp_result);

    if (!$emp) {
        die("No employee record found.");
    }

    $employee_id = $emp['employee_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Payslip</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }

        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h2 { font-size: 20px; }
        .user-info { font-size: 14px; }

        .container { display: flex; min-height: calc(100vh - 60px); }

        .sidebar { width: 250px; background: #34495e; color: white; padding: 20px 0; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 12px 20px; transition: background 0.3s; }
        .sidebar a:hover { background: #2c3e50; }
        .sidebar a.active { background: #3498db; }

        .content { flex: 1; padding: 20px; }

        .card { background: white; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 15px; color: #333; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #34495e; color: white; padding: 10px 12px; text-align: left; font-size: 14px; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        tr:hover td { background: #f9f9f9; }

        .empty-state { text-align: center; padding: 40px; color: #999; }
    </style>
</head>
<body>

<div class="header">
    <h2>APS - Automated Payroll System</h2>
    <div class="user-info">Welcome, <?php echo $_SESSION['username']; ?> (<?php echo ucfirst($_SESSION['role']); ?>)</div>
</div>

<div class="container">
    <div class="sidebar">
        <a href="employee.php">Dashboard</a>
        <a href="payslip.php" class="active">My Payslip</a>
        <a href="timesheet.php">My Timesheet</a>
        <a href="timein.php">Time In</a>
        <a href="timeout.php">Time Out</a>
        <a href="logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>
    </div>

    <div class="content">
        <div class="card">
            <h3>My Payslips</h3>

            <?php
                $result = mysqli_query($con, "SELECT * 
                                              FROM payroll 
                                              WHERE employee_id='$employee_id'
                                              AND sent_at IS NOT NULL
                                              AND sent_at != '0000-00-00 00:00:00'
                                              ORDER BY sent_at DESC");
                $count = mysqli_num_rows($result);

                if ($count == 0) {
                    echo "<div class='empty-state'><p>No payslips have been sent to you yet.</p></div>";
                } else {
                    echo "<table>";
                        echo "<tr>";
                            echo "<th>Payslip #</th>";
                            echo "<th>Total Hours</th>";
                            echo "<th>Overtime Hours</th>";
                            echo "<th>Gross Salary</th>";
                            echo "<th>Total Deductions</th>";
                            echo "<th>Total Benefits</th>";
                            echo "<th>Net Salary</th>";
                            echo "<th>Approved By</th>";
                            echo "<th>Sent At</th>";
                        echo "</tr>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                                echo "<td>" . str_pad($row['payroll_id'], 5, '0', STR_PAD_LEFT) . "</td>";
                                echo "<td>" . $row['total_hours'] . " hrs</td>";
                                echo "<td>" . $row['overtime_hours'] . " hrs</td>";
                                echo "<td>&#8369;" . number_format($row['gross_salary'], 2) . "</td>";
                                echo "<td>&#8369;" . number_format($row['total_deductions'], 2) . "</td>";
                                echo "<td>&#8369;" . number_format($row['total_benefits'], 2) . "</td>";
                                echo "<td><strong>&#8369;" . number_format($row['net_salary'], 2) . "</strong></td>";
                                echo "<td>" . ($row['approved_by'] ?: 'Pending') . "</td>";
                                echo "<td>" . $row['sent_at'] . "</td>";
                            echo "</tr>";
                        }

                        echo "<tr>";
                            echo "<td colspan='9'><strong>Total Payslips: $count</strong></td>";
                        echo "</tr>";

                    echo "</table>";
                }
            ?>

        </div>
    </div>
</div>

</body>
</html>
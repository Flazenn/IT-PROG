<?php
    session_start();
    require 'dbconnection.php';
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Send payslip
    if (isset($_GET['send'])) {
        $payroll_id = $_GET['send'];
        $sent_at    = date('Y-m-d H:i:s');

        mysqli_query($con, "UPDATE payroll 
                            SET sent_at='$sent_at'
                            WHERE payroll_id='$payroll_id'");

        header("Location: send_payslip.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Send Payslips</title>
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
            .sidebar a.active { background: #e67e22; }

            .content { flex: 1; padding: 20px; }

            .card { background: white; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .card h3 { margin-bottom: 20px; color: #333; }

            table { width: 100%; border-collapse: collapse; }
            th { background: #34495e; color: white; padding: 10px 12px; text-align: left; font-size: 14px; }
            td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: middle; }
            tr:hover td { background: #f9f9f9; }

            .btn { padding: 5px 12px; border-radius: 3px; text-decoration: none; font-size: 13px; }
            .btn-send { background: #e67e22; color: white; }
            .btn-send:hover { background: #ca6f1e; }

            .badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
            .badge-sent    { background: #d5f5e3; color: #27ae60; }
            .badge-pending { background: #fef9e7; color: #f39c12; }

            .empty-state { text-align: center; padding: 30px; color: #999; font-size: 14px; }
        </style>
    </head>

    <body>

        <div class="header">
            <h2>APS - Finance Department</h2>
            <div class="user-info">Welcome, <?php echo $_SESSION['username']; ?> (<?php echo ucfirst($_SESSION['role']); ?>)</div>
        </div>

        <div class="container">

            <div class="sidebar">
                <a href="finance.php">Dashboard</a>
                <a href="compute_salary.php">Compute Salary</a>
                <a href="approve_salary.php">Approve Salaries</a>
                <a href="ticket_request.php">Request Ticket</a>
                <a href="send_payslip.php" class="active">Send Payslips</a>
                <a href="logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>
            </div>

            <div class="content">
                <div class="card">
                    <h3>Send Payslips to Employees</h3>

                        <?php
                            $result = mysqli_query($con, "SELECT p.*, e.name 
                                                        FROM payroll p
                                                        JOIN employee_data e ON p.employee_id = e.employee_id
                                                        ORDER BY p.generated_at DESC");

                            $count = mysqli_num_rows($result);

                            if ($count == 0) {

                                echo "<div class='empty-state'>No computed payroll records found.</div>";

                            } else {

                                echo "<table>";

                                    echo "<tr>";
                                        echo "<th>Payroll #</th>";
                                        echo "<th>Employee</th>";
                                        echo "<th>Gross Salary</th>";
                                        echo "<th>Total Deductions</th>";
                                        echo "<th>Total Benefits</th>";
                                        echo "<th>Net Salary</th>";
                                        echo "<th>Generated At</th>";
                                        echo "<th>Status</th>";
                                        echo "<th>Sent At</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";

                                while ($row = mysqli_fetch_assoc($result)) {

                                    if (!empty($row['sent_at'])) {
                                        $is_sent = true;
                                    } else {
                                        $is_sent = false;
                                    }

                                    if ($is_sent) {
                                        $badge = "<span class='badge badge-sent'>Sent</span>";
                                    } else {
                                        $badge = "<span class='badge badge-pending'>Not Sent</span>";
                                    }

                                    if ($is_sent) {
                                        $action = "—";
                                    } else {
                                        $action = "<a href='send_payslip.php?send=" . $row['payroll_id'] . "' 
                                                    class='btn btn-send'
                                                    onclick='return confirm(\"Send this payslip to " . $row['name'] . "?\")'>
                                                    Send
                                                </a>";
                                    }

                                    $id = str_pad($row['payroll_id'], 5, '0', STR_PAD_LEFT);
                                    $name = ucfirst($row['name']);
                                    $gross = number_format($row['gross_salary'], 2);
                                    $deductions = number_format($row['total_deductions'], 2);
                                    $benefits = number_format($row['total_benefits'], 2);
                                    $net = number_format($row['net_salary'], 2);

                                    if (!empty($row['sent_at'])) {
                                        $sent_at = $row['sent_at'];
                                    } else {
                                        $sent_at = "—";
                                    }

                                    echo "<tr>";
                                        echo "<td>$id</td>";
                                        echo "<td>$name</td>";
                                        echo "<td>&#8369;$gross</td>";
                                        echo "<td>&#8369;$deductions</td>";
                                        echo "<td>&#8369;$benefits</td>";
                                        echo "<td><strong>&#8369;$net</strong></td>";
                                        echo "<td>" . $row['generated_at'] . "</td>";
                                        echo "<td>$badge</td>";
                                        echo "<td>$sent_at</td>";
                                        echo "<td>$action</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            }
                        ?>

                </div>
            </div>

        </div>

    </body>
</html>
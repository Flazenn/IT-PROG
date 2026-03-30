<?php
    session_start();
    require 'dbconnection.php';
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    // Submit ticket
    if (isset($_POST['submit'])) {
        $requested_by = $_SESSION['username'];
        $request_type = $_POST['srequest_type'];
        $description  = $_POST['sdescription'];
        $status       = 0;
        $created_at   = date('Y-m-d H:i:s');
        
        // Check role to determine sent_to
        if ($_SESSION['role'] == 'finance') {
            $sent_to = 'hr';
        } else {
            $sent_to = 'admin';
        }
        
        $insertQuery = "INSERT INTO tickets (requested_by, request_type, description, status, created_at, sent_to)
                        VALUES ('$requested_by', '$request_type', '$description', '$status', '$created_at', '$sent_to')";
        
        mysqli_query($con, $insertQuery);
        
        header("Location: ticket_request.php");
        exit();
    }
    
    // HR approve ticket
    if (isset($_GET['approve'])) {
        $ticket_id   = $_GET['approve'];
        $resolved_by = $_SESSION['username'];
        $resolved_at = date('Y-m-d H:i:s');
        
        $updateQuery = "UPDATE tickets 
                        SET status='1', resolved_by='$resolved_by', resolved_at='$resolved_at'
                        WHERE ticket_id='$ticket_id'";
        
        mysqli_query($con, $updateQuery);
        
        header("Location: ticket_request.php");
        exit();
    }
    
    // HR pass to admin
    if (isset($_GET['escalate'])) {
        $ticket_id = $_GET['escalate'];
        
        $updateQuery = "UPDATE tickets 
                        SET sent_to='admin'
                        WHERE ticket_id='$ticket_id'";
        
        mysqli_query($con, $updateQuery);
        
        header("Location: ticket_request.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Request Ticket</title>
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
            .sidebar a.active { background: #27ae60; }
            .content { flex: 1; padding: 20px; }
            .card { background: white; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .card h3 { margin-bottom: 20px; color: #333; }
            table { width: 100%; border-collapse: collapse; }
            th { background: #34495e; color: white; padding: 10px 12px; text-align: left; font-size: 14px; }
            td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: middle; }
            tr:hover td { background: #f9f9f9; }
            .form-table td { border-bottom: 1px solid #eee; }
            .form-table td:first-child { width: 160px; color: #555; font-weight: bold; background: #f8f9fa; }
            select, textarea {
                width: 100%;
                padding: 8px 10px;
                font-size: 14px;
                border: 1px solid #ddd;
                border-radius: 3px;
                font-family: Arial, sans-serif;
            }
            select:focus, textarea:focus { outline: none; border-color: #27ae60; }
            textarea { resize: vertical; }
            input[type="submit"] {
                padding: 9px 25px;
                background: #27ae60;
                color: white;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                font-size: 14px;
            }
            input[type="submit"]:hover { background: #219a52; }
            .btn { padding: 5px 12px; border-radius: 3px; text-decoration: none; font-size: 13px; }
            .btn-approve  { background: #27ae60; color: white; }
            .btn-escalate { background: #e67e22; color: white; }
            .btn-approve:hover  { background: #219a52; }
            .btn-escalate:hover { background: #ca6f1e; }
            .badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
            .badge-pending  { background: #fef9e7; color: #f39c12; }
            .badge-approved { background: #d5f5e3; color: #27ae60; }
            .badge-escalated { background: #fdebd0; color: #e67e22; }
            .empty-state { text-align: center; padding: 30px; color: #999; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>APS - Automated Payroll System</h2>
            <div class="user-info">Welcome, <?php echo $_SESSION['username']; ?> (<?php echo ucfirst($_SESSION['role']); ?>)</div>
        </div>
        <div class="container">
            <div class="sidebar">
                <?php
                if ($_SESSION['role'] == 'finance') {
                    echo '<a href="finance.php">Dashboard</a>';
                    echo '<a href="compute_salary.php">Compute Salary</a>';
                    echo '<a href="approve_salary.php">Approve Salaries</a>';
                    echo '<a href="ticket_request.php" class="active">Request Ticket</a>';
                    echo '<a href="send_payslip.php">Send Payslips</a>';
                    echo '<a href="logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>';
                } else {
                    echo '<a href="hr.php">Dashboard</a>';
                    echo '<a href="employees.php">Employees</a>';
                    echo '<a href="salary_data.php">Salary Data</a>';
                    echo '<a href="time_adjustment.php">Time Adjustments</a>';
                    echo '<a href="ticket_request.php" class="active">Request Ticket</a>';
                    echo '<a href="send_finance.php">Send to Finance</a>';
                    echo '<a href="logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>';
                }
                ?>
            </div>
            <div class="content">
                <?php
                if ($_SESSION['role'] == 'hr') {
                    // HR: View Finance Tickets
                    echo '<div class="card">';
                    echo '<h3>Finance Ticket Requests</h3>';
                    
                    $result = mysqli_query($con, "SELECT * FROM tickets WHERE sent_to='hr' ORDER BY created_at DESC");
                    $count  = mysqli_num_rows($result);
                    
                    if ($count == 0) {
                        echo "<div class='empty-state'>No ticket requests from Finance.</div>";
                    } else {
                        echo '<table>';
                            echo '<tr>';
                                echo '<th>Ticket #</th>';
                                echo '<th>Requested By</th>';
                                echo '<th>Request Type</th>';
                                echo '<th>Description</th>';
                                echo '<th>Status</th>';
                                echo '<th>Created At</th>';
                                echo '<th>Action</th>';
                            echo '</tr>';
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($row['status'] == 1) {
                                    $badge = "<span class='badge badge-approved'>Approved</span>";
                                } else if ($row['sent_to'] == 'admin') {
                                    $badge = "<span class='badge badge-escalated'>Escalated</span>";
                                } else {
                                    $badge = "<span class='badge badge-pending'>Pending</span>";
                                }
                                
                                // Determine actions
                                $actions = '';
                                if ($row['status'] == 0) {
                                    $actions = "<a href='ticket_request.php?approve=" . $row['ticket_id'] . "' class='btn btn-approve'>Approve</a> 
                                                <a href='ticket_request.php?escalate=" . $row['ticket_id'] . "' class='btn btn-escalate' onclick='return confirm(\"Escalate this to Admin?\")'>Escalate to Admin</a>";
                                }
                                
                                echo '<tr>';
                                    echo "<td>" . str_pad($row['ticket_id'], 5, '0', STR_PAD_LEFT) . "</td>";
                                    echo "<td>" . $row['requested_by'] . "</td>";
                                    echo "<td>" . $row['request_type'] . "</td>";
                                    echo "<td>" . $row['description'] . "</td>";
                                    echo "<td>" . $badge . "</td>";
                                    echo "<td>" . $row['created_at'] . "</td>";
                                    echo "<td>" . $actions . "</td>";
                                echo '</tr>';
                            }
                        echo '</table>';
                    }
                    echo '</div>';
                    
                    // HR: Request Permission from Admin
                    echo '<div class="card">';
                    echo '<h3>Request Permission → Admin</h3>';
                }
                
                if ($_SESSION['role'] == 'finance') {
                    echo '<div class="card">';
                    echo '<h3>Request Ticket → HR</h3>';
                }
                ?>
                
                <form action="" method="POST">
                    <table class="form-table">
                        <tr>
                            <td>Request Type</td>
                            <td>
                                <select name="srequest_type">
                                    <option value="baserate">Base Rate</option>
                                    <option value="employeestatus">Employee Status</option>
                                    <option value="addemployee">Add Employee</option>
                                    <option value="removeemployee">Remove Employee</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>
                                <textarea name="sdescription" rows="5" placeholder="Describe your request..." required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right; background: white;">
                                <input type="submit" name="submit" value="Submit Ticket">
                            </td>
                        </tr>
                    </table>
                </form>
                </div>
            </div>
        </div>
    </body>
</html>
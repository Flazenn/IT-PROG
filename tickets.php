<?php
    session_start();
    require 'dbconnection.php';
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Approve ticket
    if (isset($_GET['approve'])) {
        $ticket_id   = $_GET['approve'];
        $resolved_by = $_SESSION['username'];
        $resolved_at = date('Y-m-d H:i:s');

        mysqli_query($con, "UPDATE tickets 
                            SET status='1', resolved_by='$resolved_by', resolved_at='$resolved_at'
                            WHERE ticket_id='$ticket_id'");

        header("Location: tickets.php");
        exit();
    }

    // Reject ticket
    if (isset($_GET['reject'])) {
        $ticket_id   = $_GET['reject'];
        $resolved_by = $_SESSION['username'];
        $resolved_at = date('Y-m-d H:i:s');

        mysqli_query($con, "UPDATE tickets 
                            SET status='2', resolved_by='$resolved_by', resolved_at='$resolved_at'
                            WHERE ticket_id='$ticket_id'");

        header("Location: tickets.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Tickets</title>
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
            .sidebar a.active { background: #e74c3c; }

            .content { flex: 1; padding: 20px; }

            .card { background: white; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .card h3 { margin-bottom: 20px; color: #333; }

            table { width: 100%; border-collapse: collapse; }
            th { background: #34495e; color: white; padding: 10px 12px; text-align: left; font-size: 14px; }
            td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: middle; }
            tr:hover td { background: #f9f9f9; }

            .btn { padding: 5px 12px; border-radius: 3px; text-decoration: none; font-size: 13px; }
            .btn-approve { background: #27ae60; color: white; }
            .btn-reject  { background: #e74c3c; color: white; }
            .btn-approve:hover { background: #219a52; }
            .btn-reject:hover  { background: #c0392b; }

            .badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
            .badge-pending  { background: #fef9e7; color: #f39c12; }
            .badge-approved { background: #d5f5e3; color: #27ae60; }
            .badge-rejected { background: #fadbd8; color: #e74c3c; }

            .empty-state { text-align: center; padding: 30px; color: #999; font-size: 14px; }
        </style>
    </head>

    <body>

        <div class="header">
            <h2>APS - Admin Panel</h2>
            <div class="user-info">Welcome, <?php echo $_SESSION['username']; ?></div>
        </div>

        <div class="container">

            <div class="sidebar">
                <a href="tickets.php" class="active">Tickets</a>
                <a href="users.php">Users</a>
                <a href="adduser.php">Add User</a>
                <a href="removeuser.php">Remove User</a>
                <a href="edituserdata.php">Edit User Data</a>
                <a href="logout.php" style="border-top: 1px solid #46637f; margin-top: 20px;">Logout</a>
            </div>

            <div class="content">
                <div class="card">
                    <h3>HR Permission Requests</h3>

                    <?php
                        $result = mysqli_query($con, "SELECT * 
                                                      FROM tickets 
                                                      WHERE sent_to='admin' 
                                                      ORDER BY created_at DESC");
                        $count  = mysqli_num_rows($result);

                        if ($count == 0) {
                            echo "<div class='empty-state'>No ticket requests from HR.</div>";
                        } else {
                            echo "<table>";
                                echo "<tr>";
                                    echo "<th>Ticket #</th>";
                                    echo "<th>Requested By</th>";
                                    echo "<th>Request Type</th>";
                                    echo "<th>Description</th>";
                                    echo "<th>Status</th>";
                                    echo "<th>Created At</th>";
                                    echo "<th>Resolved By</th>";
                                    echo "<th>Resolved At</th>";
                                    echo "<th>Action</th>";
                                echo "</tr>";

                                while ($row = mysqli_fetch_assoc($result)) {
                                    if ($row['status'] == 1) {
                                        $badge = "<span class='badge badge-approved'>Approved</span>";
                                    } else if ($row['status'] == 2) {
                                        $badge = "<span class='badge badge-rejected'>Rejected</span>";
                                    } else {
                                        $badge = "<span class='badge badge-pending'>Pending</span>";
                                    }

                                    $actions = '';
                                    if ($row['status'] == 0) {
                                        $actions = "<a href='tickets.php?approve=" . $row['ticket_id'] . "' class='btn btn-approve'>Approve</a> 
                                                    <a href='tickets.php?reject=" . $row['ticket_id'] . "' class='btn btn-reject' onclick='return confirm(\"Reject this request?\")'>Reject</a>";
                                    }

                                    echo "<tr>";
                                        echo "<td>" . str_pad($row['ticket_id'], 5, '0', STR_PAD_LEFT) . "</td>";
                                        echo "<td>" . $row['requested_by'] . "</td>";
                                        echo "<td>" . $row['request_type'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $badge . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        
                                        if ($row['resolved_by']) {
                                            echo "<td>" . $row['resolved_by'] . "</td>";
                                        } else {
                                            echo "<td>—</td>";
                                        }

                                        if ($row['resolved_at']) {
                                            echo "<td>" . $row['resolved_at'] . "</td>";
                                        } else {
                                            echo "<td>—</td>";
                                        }

                                        echo "<td>" . $actions . "</td>";
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
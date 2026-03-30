<?php
require "dbconnection.php";
session_start();
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'finance') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$today = date('Y-m-d H:i:s');

// Handle single approve/reject
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    mysqli_query($con, "UPDATE finance SET status='approved', approved_by='$username', approved_at = '$today' WHERE finance_id='$id'");
    header("Location: approve_salary.php?msg=approved");
    exit();
}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    mysqli_query($con, "UPDATE finance SET status='rejected', approved_by='$username', approved_at = '$today' WHERE finance_id='$id'");
    header("Location: approve_salary.php?msg=rejected");
    exit();
}

// Handle approve all
if (isset($_GET['approve_all'])) {
    mysqli_query($con, "UPDATE finance SET status='approved', approved_by='$username', approved_at = '$today' WHERE status='pending'");
    header("Location: approve_salary.php?msg=approved_all");
    exit();
}

$result = mysqli_query($con, "SELECT * FROM finance ORDER BY date_sent DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Salary</title>
</head>
<body>
    <header>
        <h1>Approve Salary</h1>
    </header>

    <section>
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'approved')     echo "<p class='msg-success'>Record approved successfully.</p>"; ?>
            <?php if ($_GET['msg'] == 'rejected')     echo "<p class='msg-success'>Record rejected.</p>"; ?>
            <?php if ($_GET['msg'] == 'approved_all') echo "<p class='msg-success'>All pending records approved.</p>"; ?>
        <?php endif; ?>

        <a href="?approve_all=true" class="btn-approve-all"
           onclick="return confirm('Approve ALL pending records?')">
        </a>

        <table border="1" cellpadding="10">
            <tr>
                <th>Finance ID</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Base Rate</th>
                <th>Total Hours</th>
                <th>SSS</th>
                <th>PhilHealth</th>
                <th>Pag-IBIG</th>
                <th>Benefit</th>
                <th>Amount</th>
                <th>Date Sent</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['finance_id']; ?></td>
                <td><?php echo $row['employee_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['baserate']; ?></td>
                <td><?php echo $row['total_hours']; ?></td>
                <td><?php echo $row['SSS']; ?></td>
                <td><?php echo $row['PhilHealth']; ?></td>
                <td><?php echo $row['Pag-IBIG']; ?></td>
                <td><?php echo $row['benefit_name']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['date_sent']; ?></td>
                <td>
                    <span class="status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                </td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <a href="?approve=<?php echo $row['finance_id']; ?>" class="btn-approve"
                           onclick="return confirm('Approve this record?')">Approve</a>
                        <a href="?reject=<?php echo $row['finance_id']; ?>" class="btn-reject"
                           onclick="return confirm('Reject this record?')">Reject</a>
                    <?php else: ?>
                        <span style="color: gray;">No actions</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <a href="finance.php">Back to Finance</a>
    </section>
</body>
</html>

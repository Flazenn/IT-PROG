<?php
require "dbconnection.php";
mysqli_select_db($con, "apsdb");

// MARK AS RESOLVED instead of DELETE
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "UPDATE ticket SET status = 1, resolved_at = NOW() WHERE ticket_id='$id'";

    if ($con->query($sql) === TRUE) {
        echo "Ticket marked as resolved";
    } else {
        echo "Error updating ticket: " . $con->error;
    }

    
}

// ONLY SHOW UNRESOLVED TICKETS
$result = $con->query("SELECT * FROM tickets WHERE status = 0");
?>

<!DOCTYPE html>
<html>
<head>
<title>Resolve Tickets</title>
</head>
<body>

<h2>Unresolved Tickets</h2>

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Payroll ID</th>
<th>Requested By</th>
<th>Request Type</th>
<th>Description</th>
<th>Status</th>
<th>Created At</th>
<th>Action</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {

    echo "<tr>";
    echo "<td>".$row['ticket_id']."</td>";
    echo "<td>".$row['payroll_id']."</td>";
    echo "<td>".$row['requested_by']."</td>";
    echo "<td>".$row['request_type']."</td>";
    echo "<td>".$row['description']."</td>";
    echo "<td>Pending</td>";
    echo "<td>".$row['created_at']."</td>";

    // BUTTON TO MARK AS RESOLVED
    echo "<td>
            <a href='resolveticket.php?id=".$row['ticket_id']."' 
               onclick=\"return confirm('Mark this ticket as resolved?');\">
               Resolve
            </a>
          </td>";

    echo "</tr>";
}
?>

</table>

<a href="admin.php">Back to admin page</a>

</body>
</html>
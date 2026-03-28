<?php
require "dbconnection.php";
        mysqli_select_db($con, "apsdb");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE user_id='$id'";

    if ($con->query($sql) === TRUE) {
        echo "User removed successfully";
    } else {
        echo "Error deleting user: " . $con->error;
    }
    header("Location: removeuser.php");
exit();
}

$result = $con->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Remove User</title>
</head>
<body>

<h2>User List</h2>

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Username</th>
<th>Role</th>
<th>Status</th>
<th>Date Created</th>
<th>Action</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {

    $status = ($row['status'] == 1) ? "Active" : "Inactive";

    echo "<tr>";
    echo "<td>".$row['user_id']."</td>";
    echo "<td>".$row['username']."</td>";
    echo "<td>".$row['role']."</td>";
    echo "<td>".$status."</td>";
    echo "<td>".$row['datecreated']."</td>";
    echo "<td>
            <a href='removeuser.php?id=".$row['user_id']."'   
            onclick='return confirm(\"Are you sure you want to delete this user?\")'>
            Remove
            </a>
          </td>";
    echo "</tr>";
}
?>

</table>
<a href="admin.php" class="nav-link"> Back to admin page</a>

</body>
</html>

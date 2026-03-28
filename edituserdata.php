<?php
require "dbconnection.php";
mysqli_select_db($con, "apsdb");

$row = null; 
$id = null;
$updates = [];

$row = null;

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $updates = [];

    if (!empty($_POST['username'])) {
        $updates[] = "username='" . $_POST['username'] . "'";
    }

    if (!empty($_POST['password'])) {
        $updates[] = "password='" . $_POST['password'] . "'";
    }

    if (!empty($_POST['role'])) {
        $updates[] = "role='" . $_POST['role'] . "'";
    }

    if (isset($_POST['status'])) {
        $updates[] = "status='" . $_POST['status'] . "'";
    }

    if (!empty($updates) && !empty($id)) {
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE user_id='$id'";
        $con->query($sql);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
</head>
<body>

<h2>Edit User</h2>


<form method="POST">
ID number:<br>
<input type="text" name="id" >
<br><br>
Username:<br>
<input type="text" name="username" >
<br><br>

Password:<br>
<input type="text" name="password"  >
<br><br>

Role:<br>
<input type="text" name="role" >
<br><br>

Status:<br>
<select name="status">
    <option value="1" >Active</option>
    <option value="0" >Inactive</option>
</select>

<br><br>

<input type="submit" name="update" value="Update User">

</form>



<br>
<a href="admin.php">Back</a>

</body>
</html>

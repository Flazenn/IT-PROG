<?php
require "dbconnection.php";
mysqli_select_db($con, "APSDB");


if(isset($_GET['id'])){
    $id = $_GET['id'];

    $result = $con->query("SELECT * FROM users WHERE user_id='$id'");
    $row = $result->fetch_assoc();
}

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE users 
            SET username='$username',
                password='$password',
                role='$role',
                status='$status'
            WHERE user_id='$id'";

    if($con->query($sql) === TRUE){
        echo "User updated successfully";
    } else {
        echo "Error updating user: " . $con->error;
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

<input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">

Username:<br>
<input type="text" name="username" value="<?php echo $row['username']; ?>" required>
<br><br>

Password:<br>
<input type="text" name="password" value="<?php echo $row['password']; ?>" required>
<br><br>

Role:<br>
<input type="text" name="role" value="<?php echo $row['role']; ?>" required>
<br><br>

Status:<br>
<select name="status">
    <option value="1" <?php if($row['status']==1) echo "selected"; ?>>Active</option>
    <option value="0" <?php if($row['status']==0) echo "selected"; ?>>Inactive</option>
</select>

<br><br>

<input type="submit" name="update" value="Update User">

</form>

<br>
<a href="admin.php">Back</a>

</body>
</html>

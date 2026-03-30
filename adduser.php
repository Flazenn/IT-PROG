 <?php
require "dbconnection.php";
mysqli_select_db($con, "apsdb");

if (isset($_POST['add'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $baserate = $_POST['baserate'];

    $status = 1;
    $datecreated = date("Y-m-d");

    
    $sql1 = "INSERT INTO users (password, datecreated, status, role, username)
             VALUES ('$password', '$datecreated', '$status', '$role', '$username')";

    $temp = $con->query($sql1);
    if ($temp == TRUE && $role == "Employee") {

        
        $user_id = $con->insert_id;

        
        $sql2 = "INSERT INTO employee_data (user_id, name, baserate, mandatory_deduction, marital_status, date_hired)
                 VALUES ('$user_id', '$username', '$baserate', '0', 'single', CURDATE())";

        if ($con->query($sql2) === TRUE) {
            echo "User added successfully";
        } else {
            echo "Employee insert error: " . $con->error;
        }

    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add User</title>
</head>
<body>

<h2>Add User</h2>

<form method="POST">
    Username:<br>
    <input type="text" name="username" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    Role:<br>
    <select name="role">
    <option value="Employee" >Employee</option>
    <option value="HR" >HR</option>
    <option value="Finance" >Finance</option>
    <option value="Admin" >Admin</option>
</select>
<br><br>
Baserate:<br>
    <input type="number" name="baserate" required><br><br>



    <input type="submit" name="add" value="Add User">
</form>

 <a href="admin.php" class="nav-link"> Back to admin page</a>

</body>
</html>

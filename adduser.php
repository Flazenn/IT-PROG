 <?php
require "dbconnection.php";
mysqli_select_db($con, "apsdb");

if (isset($_POST['add'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $baserate = $_POST['baserate'];
    $marital = $_POST['marital'];

    $status = 1;
    $datecreated = date("Y-m-d");

    
    $sql1 = "INSERT INTO users (password, datecreated, status, role, username)
             VALUES ('$password', '$datecreated', '$status', '$role', '$username')";

    if ($con->query($sql1) === TRUE && $role == "Employee") {
        
        $user_id = $con->insert_id;

        $sql2 = "INSERT INTO employee_data (user_id, name, baserate, mandatory_deduction, marital_status, date_hired)
                 VALUES ('$user_id', '$username', '$baserate', 'SSS,Philhealth,Pag-IBIG', '$marital', CURDATE())";

        if ($con->query($sql2) === TRUE) {

            $new_employee_id = $con->insert_id;

            $sss = 500.00;
            $pagibig = 100.00;
            $monthly_salary = $baserate * 40 * 4;
            $philhealth = ($monthly_salary * 0.05) / 2;

            $sql3 = "INSERT INTO employee_deductions 
                     (employee_id, SSS, PhilHealth, `Pag-IBIG`)
                     VALUES ('$new_employee_id', '$sss', '$philhealth', '$pagibig')";

            if ($con->query($sql3) === TRUE) {

                $benefit_name = 'Standard Benefit';
                $benefit_amount = ($marital == 'married') ? 500.00 : 0.00;

                $sql4 = "INSERT INTO benefits (employee_id, benefit_name, amount)
                        VALUES ('$new_employee_id', '$benefit_name', '$benefit_amount')";

                if ($con->query($sql4) === TRUE) {
                    echo "User and employee added successfully";
                } else {
                    echo "Benefits error: " . $con->error;
                }

            } else {
                echo "Deductions error: " . $con->error;
            }
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
    Marital Status:<br>
    <select name="marital">
    <option value="single" >Single</option>
    <option value="married" >Married</option>
</select>
<br><br>


    <input type="submit" name="add" value="Add User">
</form>

 <a href="admin.php" class="nav-link"> Back to admin page</a>

</body>
</html>

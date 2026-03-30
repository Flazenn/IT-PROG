<?php
require "dbconnection.php";
mysqli_select_db($con, "apsdb");

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    die("Access denied. HR only.");
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$sql = "SELECT 
            e.employee_id,
            e.name,
            e.baserate,
            e.mandatory_deduction AS mandatory_status,
            COALESCE(SUM(a.total_hours), 0) AS total_hours,
            COALESCE((ed.SSS + ed.PhilHealth + ed.`Pag-IBIG`), 0) AS total_deductions,
            COALESCE(SUM(b.amount), 0) AS total_benefits
        FROM employee_data e
        LEFT JOIN attendance a 
            ON e.employee_id = a.employee_id
        LEFT JOIN employee_deductions ed 
            ON e.employee_id = ed.employee_id
        LEFT JOIN benefits b 
            ON e.employee_id = b.payroll_id
        WHERE e.name LIKE '%$search%'
        GROUP BY e.employee_id, e.name, e.baserate, e.mandatory_deduction, ed.SSS, ed.PhilHealth, ed.`Pag-IBIG`";

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee Salary Data</title>
</head>
<body>

<h2>Employee Salary Data</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search by name" value="<?php echo $search; ?>">
    <input type="submit" value="Search">
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>Employee ID</th>
    <th>Name</th>
    <th>Base Rate</th>
    <th>Mandatory Status</th>
    <th>Total Hours</th>
    <th>Total Deductions</th>
    <th>Total Benefits</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row['employee_id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['baserate']."</td>";
    echo "<td>".$row['mandatory_status']."</td>";
    echo "<td>".$row['total_hours']."</td>";
    echo "<td>".$row['total_deductions']."</td>";
    echo "<td>".$row['total_benefits']."</td>";
    echo "</tr>";
}
?>

</table>

<br>
<a href="hr.php">Back to HR page</a>

</body>
</html>

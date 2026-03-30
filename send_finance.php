<?php
require "dbconnection.php";
mysqli_select_db($con, "apsdb");

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    die("Access denied. HR only.");
}

if (isset($_GET['send_id'])) {

    $id = $_GET['send_id'];

    $sql = "INSERT INTO finance (employee_id, name, baserate, total_hours, SSS, PhilHealth, `Pag-IBIG`, benefit_name, amount, date_sent)
            SELECT 
                e.employee_id,
                e.name,
                e.baserate,
                p.total_hours,
                d.SSS,
                d.PhilHealth,
                d.`Pag-IBIG`,
                'Standard Benefit',
                p.total_benefits,
                NOW() 
            FROM employee_data e
            LEFT JOIN payroll p ON e.employee_id = p.employee_id
            LEFT JOIN employee_deductions d ON e.employee_id = d.employee_id
            WHERE e.employee_id = '$id'";

    mysqli_query($con, $sql);
}

if (isset($_GET['send_all'])) {

    $sql = "INSERT INTO finance (employee_id, name, baserate, total_hours, SSS, PhilHealth, `Pag-IBIG`, benefit_name, amount, date_sent)
            SELECT 
                e.employee_id,
                e.name,
                e.baserate,
                p.total_hours,
                d.SSS,
                d.PhilHealth,
                d.`Pag-IBIG`,
                'Standard Benefit',
                p.total_benefits,
                NOW()
            FROM employee_data e
            LEFT JOIN payroll p ON e.employee_id = p.employee_id
            LEFT JOIN employee_deductions d ON e.employee_id = d.employee_id";

    mysqli_query($con, $sql);
}

$result = mysqli_query($con, "SELECT 
            e.employee_id,
            e.name,
            e.baserate,
            e.mandatory_deduction,
            p.total_hours,
            p.overtime_hours,
            p.total_deductions,
            p.total_benefits
        FROM employee_data e
        LEFT JOIN payroll p ON e.employee_id = p.employee_id");
?>

<!DOCTYPE html>
<html>
<head>
<title>Send Salary to Finance</title>
</head>
<body>

<h2>Employee Salary Data (HR)</h2>

<?php
if (isset($_GET['msg'])) {

    if ($_GET['msg'] == "sent_one") {
        echo "<p style='color:green;'>Employee salary successfully sent to Finance.</p>";
    }

    if ($_GET['msg'] == "sent_all") {
        echo "<p style='color:green;'>All employee salary data successfully sent to Finance.</p>";
    }

    if ($_GET['msg'] == "error") {
        echo "<p style='color:red;'>Error sending data.</p>";
    }
}
?>

<a href="?send_all=true" 
   onclick="return confirm('Are you sure you want to send ALL employee salary data to Finance?')">
    <button>Send All to Finance</button>
</a>

<br><br>

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Name</th>
<th>Base Rate</th>
<th>Mandatory</th>
<th>Total Hours</th>
<th>OT Hours</th>
<th>Deductions</th>
<th>Benefits</th>
<th>Action</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {

    $total_hours = $row['total_hours'] ?? 0;
    $overtime_hours = $row['overtime_hours'] ?? 0;
    $total_deductions = $row['total_deductions'] ?? 0;
    $total_benefits = $row['total_benefits'] ?? 0;

    echo "<tr>";
    echo "<td>".$row['employee_id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['baserate']."</td>";
    echo "<td>".$row['mandatory_deduction']."</td>";
    echo "<td>".$total_hours."</td>";
    echo "<td>".$overtime_hours."</td>";
    echo "<td>".$total_deductions."</td>";
    echo "<td>".$total_benefits."</td>";

    echo "<td>
            <a href='?send_id=".$row['employee_id']."' 
               onclick=\"return confirm('Send this employee data to Finance?')\">
               <button>Send</button>
            </a>
          </td>";

    echo "</tr>";
}
?>

</table>

<br>
<a href="hr.php">Back to HR page</a>

</body>
</html>

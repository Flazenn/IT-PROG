<?php
require "dbconnection.php";

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    die("Access denied. HR only.");
}

if (isset($_GET['send_id'])) {
    $employee_id = $_GET['send_id'];
    
    $check = mysqli_query($con, "SELECT * FROM finance 
                                  WHERE employee_id = '$employee_id' 
                                  AND status != 'rejected'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('This employee already has a pending or approved record!');
                window.location.href = 'send_finance.php';
              </script>";
        exit();
    }
    $id = $employee_id;

    $sql = "INSERT INTO finance (employee_id, name, baserate, total_hours, SSS, PhilHealth, `Pag-IBIG`, benefit_name, amount, date_sent)
            SELECT 
                e.employee_id,
                e.name,
                e.baserate,
                IFNULL(SUM(a.total_hours), 0),
                IFNULL(d.SSS, 0),
                IFNULL(d.PhilHealth, 0),
                IFNULL(d.`Pag-IBIG`, 0),
                IFNULL(b.benefit_name, 'N/A'),
                IFNULL(SUM(b.amount), 0),
                NOW()
            FROM employee_data e
            LEFT JOIN attendance a ON e.employee_id = a.employee_id
            LEFT JOIN employee_deductions d ON e.employee_id = d.employee_id
            LEFT JOIN benefits b ON e.employee_id = b.employee_id
            WHERE e.employee_id = '$id'
            GROUP BY e.employee_id, e.name, e.baserate, d.SSS, d.PhilHealth, d.`Pag-IBIG`, b.benefit_name";

    mysqli_query($con, $sql);
}

if (isset($_GET['send_all'])) {
    $check = mysqli_query($con, "SELECT * FROM finance 
                                 WHERE status != 'rejected'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('Some employees already have pending or approved records!');
                window.location.href = 'send_finance.php';
              </script>";
        exit();
    }

    $sql = "INSERT INTO finance (employee_id, name, baserate, total_hours, SSS, PhilHealth, `Pag-IBIG`, benefit_name, amount, date_sent)
            SELECT 
                e.employee_id,
                e.name,
                e.baserate,
                IFNULL(SUM(a.total_hours), 0),
                IFNULL(d.SSS, 0),
                IFNULL(d.PhilHealth, 0),
                IFNULL(d.`Pag-IBIG`, 0),
                IFNULL(b.benefit_name, 'N/A'),
                IFNULL(SUM(b.amount), 0),
                NOW()
            FROM employee_data e
            LEFT JOIN attendance a ON e.employee_id = a.employee_id
            LEFT JOIN employee_deductions d ON e.employee_id = d.employee_id
            LEFT JOIN benefits b ON e.employee_id = b.employee_id
            GROUP BY e.employee_id, e.name, e.baserate, d.SSS, d.PhilHealth, d.`Pag-IBIG`, b.benefit_name";

    mysqli_query($con, $sql);
}

$result = mysqli_query($con, "SELECT 
            e.employee_id,
            e.name,
            e.baserate,
            e.mandatory_deduction,
            IFNULL(SUM(a.total_hours), 0) AS total_hours,
            IFNULL(d.SSS, 0) + IFNULL(d.PhilHealth, 0) + IFNULL(d.`Pag-IBIG`, 0) AS total_deductions,
            IFNULL(SUM(b.amount), 0) AS total_benefits
        FROM employee_data e
        LEFT JOIN attendance a ON e.employee_id = a.employee_id
        LEFT JOIN employee_deductions d ON e.employee_id = d.employee_id
        LEFT JOIN benefits b ON e.employee_id = b.employee_id
        GROUP BY e.employee_id, e.name, e.baserate, e.mandatory_deduction, d.SSS, d.PhilHealth, d.`Pag-IBIG`");
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
    <th>Deductions</th>
    <th>Benefits</th>
    <th>Action</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {

    $total_hours      = $row['total_hours'] ?? 0;
    $total_deductions = $row['total_deductions'] ?? 0;
    $total_benefits   = $row['total_benefits'] ?? 0;

    echo "<tr>";
    echo "<td>".$row['employee_id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['baserate']."</td>";
    echo "<td>".$row['mandatory_deduction']."</td>";
    echo "<td>".$total_hours."</td>";
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

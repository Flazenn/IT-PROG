<?php
    session_start();
    date_default_timezone_set('Asia/Manila');

    require 'dbconnection.php';

    if ($_SESSION['role'] != 'finance' && !isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $result = mysqli_query($con, "SELECT * FROM finance WHERE status = 'approved'");
?>

 <html>
    <head>
        <link rel = "stylesheet" href = "style.css">
    </head>
    <body>
        <header>
            <h1>Weekly Salary Computation</h1>
        </header>

        <section>
            <form method = "post" action = "">
                Please Select Which Employee:
                <select name = "employee_id">
                    
                    <?php
                        while($emp = mysqli_fetch_assoc($result)){
                            echo "<option value ='" . $emp['employee_id'] . "'>";
                                echo $emp['employee_id'] . ' (' . $emp['name'] . ')';
                            echo "</option>";
                        }
                    ?>
                    </select><br><br>
                
                Select Week:    
                <input type = "week" name = "week"><br><br><br>
                <input type = "submit" value = "Compute">
            </form>
        </section>
  


<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $employee_id = $_POST['employee_id'];
        $week = $_POST['week'];
        $week_start = date('Y-m-d', strtotime($week));
        $week_end = date('Y-m-d', strtotime($week . ' +6 days'));
    
        $result = mysqli_query($con, "SELECT * FROM finance WHERE status = 'approved' AND employee_id = '$employee_id'");
        $row = mysqli_fetch_assoc($result);

        $baserate = $row['baserate'];
        $total_hours = $row['total_hours'] ?? 0;

        $regular_hours = min($total_hours, 40);
        $overtime_hours = max(0, $total_hours - 40);
        $base_pay = $regular_hours * $baserate;
        $overtime_pay = $overtime_hours * ($baserate * 1.25);
        $gross_salary = $base_pay + $overtime_pay;

        $sss = $row['SSS'];
        $philhealth = $row['PhilHealth'];
        $pagibig = $row['Pag-IBIG'];

        $total_deductions = $sss + $philhealth + $pagibig;
        $total_benefits = $row['total_benefits'] ?? 0;

        $approved_by = $row['approved_by'];

        $net_salary = $gross_salary + $total_benefits - $total_deductions;

        $generated_at = date('Y-m-d H:i:s');

        $approved_at = $row['approved_at'];

        $insert = "INSERT INTO payroll (employee_id, total_hours, overtime_hours, gross_salary, 
                                        total_deductions, total_benefits, net_salary, approved_by,
                                        approved_at, generated_at, week_start, week_end)
                   VALUES('$employee_id','$total_hours','$overtime_hours','$gross_salary',
                          '$total_deductions', '$total_benefits', '$net_salary', '$approved_by',
                          '$approved_at', '$generated_at', '$week_start', '$week_end')";

        mysqli_query($con, $insert);
        }
?>
    </body>
</html>

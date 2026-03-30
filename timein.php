<?php
    session_start();
    date_default_timezone_set('Asia/Manila');

    require 'dbconnection.php';

    if ($_SESSION['role'] != 'hr' && $_SESSION['role'] != 'Employee' && !isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $row = null;
    $employee_id = null;

    if ($_SESSION['role'] == 'Employee'){
        $result = mysqli_query($con, "SELECT * FROM employee_data WHERE user_id='$user_id'");
        $row = mysqli_fetch_assoc($result);
        $employee_id = $row['employee_id'];

        $today = date('Y-m-d');
        $check = mysqli_query($con, "SELECT * FROM attendance 
                                WHERE employee_id='$employee_id' 
                                AND DATE(time_in) = '$today'");

    if (mysqli_num_rows($check) > 0){
        echo "<script>
                alert('You are already clocked in for today!');
                window.location.href = 'employee.php'
              </script>";
        exit();
        }
    }
 ?>

 <html>
    <head>
        <link rel = "stylesheet" href = "style.css">
    </head>
    <body>
        <header>
            <h1>TIME-IN FORM</h1>
        </header>

        <section>
            <h2>Welcome, <?php echo $_SESSION['username']?>!</h2><br>
            <p>Current Date: <?php echo date('F j, Y')?></p><br>  
            <p>Current Time: <?php echo date('H:i:s')?></p><br><br>   
            <form method = "post" action = "">

                <?php

                    $week_start = date('Y-m-d', strtotime('monday this week'));
                    $week_end   = date('Y-m-d', strtotime('sunday this week'));

                    $select = mysqli_query($con, "SELECT e.employee_id, e.name 
                                                  FROM employee_data e
                                                  WHERE e.employee_id NOT IN (
                                                  SELECT employee_id 
                                                  FROM attendance 
                                                  WHERE DATE(time_in) BETWEEN '$week_start' AND '$week_end'
                                                )");


                    if ($_SESSION['role'] == 'Employee'){
                        echo "<input type='hidden' name='employee_id' value='" . $row['employee_id'] . "'>";
                    } else {
                        echo "Select Employee: ";
                        echo "<select name = 'employee_id'>";

                        while($emp = mysqli_fetch_assoc($select)){
                            echo "<option value ='" . $emp['employee_id'] . "'>";
                                echo $emp['employee_id'] . ' (' . $emp['name'] . ')';
                            echo "</option>";
                        }
                        echo "</select><br><br>";
                    }  
                ?>

                <input type = "submit" value = "CLOCK IN">
            </form>
        </section>
<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $employee_id = $_POST['employee_id'];
        $encoded_by = $_SESSION['username'];
        $time_in = date('Y-m-d H:i:s');
        // $time_in = "2026-04-3 9:00:00";

        $query = "INSERT INTO attendance (employee_id, encoded_by, time_in)
                  VALUES ('$employee_id', '$encoded_by', '$time_in')";
        
        mysqli_query($con, $query);

        if ($_SESSION['role'] == 'Employee'){
            header("Location: employee.php");
            exit();
        } else {
            header("Location: hr.php");
            exit();
        }    
    }
        
?>

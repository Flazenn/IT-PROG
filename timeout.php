<?php
    session_start();
    date_default_timezone_set('Asia/Manila');

    require 'dbconnection.php';

    if ($_SESSION['role'] != 'hr' && $_SESSION['role'] != 'employee' && !isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($con, "SELECT * FROM employee_data WHERE user_id='$user_id'");
    $row = mysqli_fetch_assoc($result);

    $employee_id = $row['employee_id'];
    $today = date('Y-m-d');

    $check = mysqli_query($con, "SELECT * FROM attendance 
                                WHERE employee_id='$employee_id' 
                                AND DATE(time_in) = '$today'
                                AND time_out IS NULL");

    if (mysqli_num_rows($check) == 0){
        echo "<script>
                alert('You are already clocked out for today!');
                window.location.href = 'employee.php'
              </script>";
        exit();
    }
 ?>

 <html>
    <head>
        <link rel = "stylesheet" href = "style.css">
    </head>
    <body>
        <header>
            <h1>TIME-OUT FORM</h1>
        </header>

        <section>
            <h2>Welcome, <?php echo $_SESSION['username']?>!</h2><br>
            <p>Current Date: <?php echo date('F j, Y')?></p><br>  
            <p>Current Time: <?php echo date('H:i:s')?></p><br><br>   
            <form method = "post" action = "">
                <input type = "hidden" name = "employee_id" value = "<?php echo $row['employee_id']?>">
                <input type = "submit" value = "CLOCK OUT">
            </form>
        </section>
<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $employee_id = $_POST['employee_id'];
        $today = date('Y-m-d');
        $time_out = date('Y-m-d H:i:s');
        // $test_time_out = "2026-03-29 17:00:00"; (CHANGE INTO THE DATE/TIME YOU WANT)
    
        $fetch = mysqli_query($con, "SELECT time_in FROM attendance 
                                    WHERE employee_id='$employee_id' 
                                    AND DATE(time_in) = '$today'
                                    AND time_out IS NULL");
        $record = mysqli_fetch_assoc($fetch);
        $time_in = $record['time_in'];

        $totalSeconds = strtotime($time_out) - strtotime($time_in);
        $totalHours = round($totalSeconds / 3600, 2);


        $query = "UPDATE attendance 
                  SET time_out = '$time_out',
                      total_hours = '$totalHours'
                  WHERE employee_id = '$employee_id' 
                  AND DATE(time_in) = '$today'
                  AND time_out IS NULL";
        
        mysqli_query($con, $query);

        header("Location: employee.php");
        exit();
    }
        
?>

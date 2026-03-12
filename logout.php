<?php
session_start();
session_destroy();
header("Location: qwerty.php");
exit();
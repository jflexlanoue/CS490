<?php
session_start();
include("Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
    $redirectToLogin = true;
}
?>



<html>
    <head>
        <title>Exam Creation</title>
    </head>
    <body>
        <a href="instructor.php">Return to main page</a><br>
    </body>
</html>

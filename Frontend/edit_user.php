<?php
include("Util/Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
    $redirectToLogin = true;
}
?>

<html>
    <head>
        <title>Edit registration</title>
    </head>
    <body>
        <a href="index.php">Return to main page</a><br>
        <center>
            <h2>Edit registration</h2>
            Enter current password: <input id="password" type="password" name="password" placeholder="Current password" autofocus><br><br>
            Enter new password: <input id= "password" type="password" name="password" placeholder="New password"><br><br>
            Confirm new password: <input id= "password" type="password" name="password" placeholder="Confirm new password"><br><br>
            <form action="">
                <input type="radio" name="accType" value="Student" checked> Student<br>
                <input type="radio" name="accType" value="Instructor"> Instructor<br>
            </form>
            <input name="loginButton" type="submit" value="Submit" onclick=""><br><br>
        </center>
    </body>
</html>
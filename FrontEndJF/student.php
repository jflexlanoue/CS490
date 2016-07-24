<?php
session_start();
include("Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    $redirectToLogin = true;
}
?>

<html>
    <head>
        <title>Student</title>
    </head>

    <script>
<?php
if ($redirectToLogin) {
    echo "window.location = 'index.php';";
}
?>
    </script>

    <body>


        <a href="edit_user.php">Account settings</a><br>
    <center>
        <h1>Student</h1>
    </center>

</body>
</html>
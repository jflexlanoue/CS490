<?php
session_start();
include("Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
    $redirectToLogin = true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>CS 490 - Instructor</title>
        <script>
<?php
if ($redirectToLogin) {
    echo "window.location = 'index.php';";
}
?>
        </script>
    </head>
    <body>

        <a href="index.php?logout=1" >Logout</a>
        <a href="edit_user.php"style="float:right">Account settings</a><br>
    <center>
        <h1>Instructor</h1>
        <a href="question_creation.php">Question Creation</a><br>
        <a href="exam_creation.php">New Exam</a><br>
        <h1>Published Exams</h1>
        <br>
    </center>

</body>
</html>








<html>
    <head>
        <title></title>
    </head>
    <body>

    </body>
</html>
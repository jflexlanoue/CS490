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
<?php
if ($redirectToLogin) {
    echo "window.location = 'index.php';";
}
?>
    </head>
    <body>
        <a href="edit_user.php" style="float:right">Account settings</a><br>
        <a href="index.php?logout=1" style="float:right">Logout</a>
    <center>
        <h1>Instructor</h1>
        <a href="question_creation.php">Question Bank</a><br>
        <a href="exam_creation.php">New Exam</a><br>
        <h1>Exams</h1>
        <div id="list">
            <?php
            
            function print_exam($exam)
            {
                echo "<div class='exam'>";
                echo "<div>ID: " . $exam["id"] . "</>";
                echo "<div>Title: " . $exam["title"] . "</div>";
                echo "<div>" . $exam["Released"]==1 ? "Released" : "Not released" . "</span>";
                echo "<div>Questions: " . count($exam["sharedQuestion"]) . "</div>";
                echo "</div>";
            }

            $exams = util::ForwardGetRequest("exam.php");
            foreach ($exams["result"] as $exam)
            {
                echo "<li>" . print_exam($exam) . "</li>";
            }

            ?>
        </div>
        <br>
    </center>

</body>
</html>
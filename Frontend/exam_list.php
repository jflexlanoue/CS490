<?php
session_start();
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole('student');

$message = "";

hdr("Available Exams");
$response = util::ForwardGetRequest("exam.php");
if($response["success"] == false)
    die("Error: " . $response["error"]);
$response = $response["result"];
?>

<a href="student.php" style="float:left">Return to main page</a>
<div style="text-align: center;">
    <h1>Available Exams</h1>

    <?php
    foreach($response as $exam) {

        echo ' 
            <div>
                <div><a href="take_exam.php?id=' . $exam["id"] . '">Exam: ' . $exam["title"] . '</a></div>
                Questions: ' . count($exam["sharedQuestion"]) .'
            </div>
        ';
    }
    ?>

</div>

<?php
footer();
?>
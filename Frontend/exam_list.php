<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole('student');

$response = util::ForwardGetRequest("exam.php");
if($response["success"] == false)
    die("Error: " . $response["error"]);
$view["exams"] = $response["result"];

foreach($view["exams"] as &$exam) {
    $exam["questioncount"] = count($exam["ownExamquestion"]);
}

$view["title"] = "Exam List";
view();
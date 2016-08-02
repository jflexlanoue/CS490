<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole('student');

$exam_id = $_POST["examid"];

util::Redirect("exam_complete.php");
ob_end_clean();
header("Connection: close");
ignore_user_abort(true);
set_time_limit(1800);
ob_start();
header("Content-Length: 0");
ob_end_flush();
flush();
session_write_close();

foreach ($_POST as $item => $answer) {
    if(substr($item, 0, 6) === "answer") {
        $question_id = (int)substr($item, 6);

        $result = Array();
        $result["studentID"] = util::GetUserID();
        $result["examID"] = $exam_id;
        $result["questionID"] = $question_id;
        $result["score"] = 0;
        $result["studentAnswer"] = $answer;
        $result["feedback"] = "";

        $results = util::ForwardPostRequest("result.php", $result);
        if($results["success"] != true) {
            $resulterror = $results["error"];
        }
    }
}

$grading_results = util::httpPost("https://web.njit.edu/~jl366/grade.php?examid=".$exam_id, true, true);

if(isset($resulterror))
    die($resulterror);


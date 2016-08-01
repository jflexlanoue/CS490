<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole('student');

$response = util::ForwardGetRequest("exam.php");
if($response["success"] == false)
    die("Error: " . $response["error"]);
$view["exams"] = $response["result"];

$exams = array();
$results = util::ForwardGetRequest("result.php", array("studentID" => util::GetUserID()));
foreach ($results["result"] as $r) {
    if(!in_array($r["exam_id"], $exams))
        $exams []= $r["exam_id"];
}

foreach($view["exams"] as $k => $v) {
    if(in_array($v["id"], $exams)) {
        unset($view["exams"][$k]);
    }
}

foreach($view["exams"] as &$exam) {
    $exam["questioncount"] = count($exam["ownExamquestion"]);
}

$view["title"] = "Exam List";
view();
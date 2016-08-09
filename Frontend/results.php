<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

if (isset($_POST['submitchanges'])){
        util::ForwardPatchRequest("result.php", $_POST);
}

$questioncache = array();
function question_by_id($id) {
    global $questioncache;
    if(!isset($questioncache[$id])) {
        $questioncache[$id] = util::ForwardGETRequest("question.php", array("id" => $id))["result"];
    }
    return $questioncache[$id];
}
$examcache = array();
function exam_by_id($id) {
    global $examcache;
    if(!isset($examcache[$id])) {
        $res = $examcache[$id] = util::ForwardGETRequest("exam.php", array("id" => $id));
        if(isset($res["result"]))
            $examcache[$id] = util::ForwardGETRequest("exam.php", array("id" => $id))["result"];
        else
            $examcache[$id] = array("title" => "[deleted exam]");
    }
    return $examcache[$id];
}
$studentcache = array();
function student_by_id($id) {
    global $studentcache;
    if(!isset($studentcache[$id])) {
        $studentcache[$id] = util::ForwardGETRequest("user.php", array("id" => $id))["result"];
    }
    return $studentcache[$id];
}
$id = array();
if(!util::IsInstructor()) {
    $id["studentID"] = util::GetUserID();
} else {
    if(isset($_GET["student"]) && !empty($_GET["student"])) {
        $id["studentID"] = $_GET["student"];
        $view["student_filter"] = $id["studentID"];
    }
    if(isset($_GET["exam"]) && !empty($_GET["exam"])) {
        $id["examID"] = $_GET["exam"];
        $view["exam_filter"] = $id["examID"];
    }
    if(isset($_GET["question"]) && !empty($_GET["question"])) {
        $id["questionID"] = $_GET["question"];
        $view["question_filter"] = $id["questionID"];
    }
}
$resultsRetrieval = util::ForwardGETRequest("result.php", $id);
$exams = array();
foreach ( $resultsRetrieval['result'] as $q ) {
    $item = array();
    $item["exam_title"] = exam_by_id($q['exam_id'])["title"];
    $item["id"] = $q['id'];
    $item["question"] = question_by_id($q['question_id'])["question"];
    $item["student"] = student_by_id($q['student_id']);
    $item["student_answer"] = $q['student_answer'];
    if(util::IsInstructor() || isset(exam_by_id($q["exam_id"])["released"]) ) {
        $item["score"] = $q['score'];
        $feedback = json_decode($q['feedback']);
        $item["feedback"] = $feedback;
    } else{
        $item["score"] = "N/A";
        $item["feedback"] = "Exam not released";
    }
    $exams[] = $item;
}

$view["exams"] = $exams;
$view["exam_list"] = $examcache;
$view["question_list"] = $questioncache;
$view["student_list"] = $studentcache;
$view["title"] = "Results";
$view["instructor"] = util::IsInstructor();
if(util::IsInstructor())
    view("results_instructor");
else
    view("results_student");

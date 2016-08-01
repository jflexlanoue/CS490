<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

if(util::IsInstructor())
    $template = "instructor_template";

function question_by_id($id) {
    global $questioncache;
    if(!isset($questioncache[$id])) {
        $questioncache[$id] = util::ForwardGETRequest("question.php", array("id" => $id))["result"];
    }
    return $questioncache[$id];
}

function exam_by_id($id) {
    global $examcache;
    if(!isset($examcache[$id])) {
        $examcache[$id] = util::ForwardGETRequest("exam.php", array("id" => $id))["result"];
    }
    return $examcache[$id];
}

$id = array();

$resultsRetrieval = util::ForwardGETRequest("result.php", $id);

$exams_edit = array();
foreach ( $resultsRetrieval['result'] as $q ) {
    $item = array();

    $item["exam_title"] = exam_by_id($q['exam_id'])["title"];
    $item["question"] = question_by_id($q['question_id'])["question"];
    $item["student_answer"] = $q['student_answer'];

    if(util::IsInstructor() || exam_by_id($q["exam_id"])["released"] == 1) {
        $item["score"] = $q['score'];
        $item["feedback"] = $q['feedback'];
    } else{
        $item["score"] = "N/A";
        $item["feedback"] = "Exam not released";
    }

    $exams_edit[] = $item;
}

$view["exams_edit"] = $exams_edit;
$view["title"] = "Results";
$view["instructor"] = util::IsInstructor();

if(util::IsInstructor())
    view("feedback_edit");

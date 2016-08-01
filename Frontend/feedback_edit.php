<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

if(util::IsInstructor())
    $template = "instructor_template";

$id["id"] = $_GET["id"];

$response = util::ForwardGETRequest("result.php", $id);

// echo json_encode($response);

$exam["score"] = $response["result"]["score"];
$exam["feedback"] = $response["result"]["feedback"];

// $exam = array();

$exam["id"] = $_GET["id"];
// $exam_edit["score"] = $_POST["points"];
// $exam_edit["feedback"] = $_POST["feedback"];

// echo json_encode($exam["feedback"]);
// $view["exam_edit"] = $exam_edit;


$view["exam"] = $exam;

if(util::IsInstructor())
    view("feedback_edit");
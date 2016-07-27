<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("instructor");

if(isset($_POST["action"])) {
    if($_POST["action"] === "release") {
        $id = $_POST["release"];
        $response = util::ForwardPatchRequest('exam.php', Array("id" => $id, "released" => true));
        if($response["success"] == false)
            die($response["error"]); // TODO pretty error handling - GH
    }
    util::Redirect('instructor.php'); // Make browser do a GET. I think? This should make refreshing the page not give a resend confirmation - GH
}

function print_exam($exam)
{
    $exam_html = '<a class="panel-block is-active" href="exam_edit.php?id={{exam_id}}">
                  <span class="panel-icon">
                    <i class="fa fa-book"></i>
                  </span>
                      {{exam_name}}
                  </a>';

    return render($exam_html, array(
        "exam_id" => $exam["id"],
        "exam_name" => $exam["title"],
        "question_count" => count($exam["ownExamquestion"]),
        "exam_released" => ($exam["released"] ==1 ? "Released" : "Not released")));
}

$view["exams"] = '';
$exams = util::ForwardGetRequest("exam.php");
foreach ($exams["result"] as $exam)
{
    $view["exams"] .= print_exam($exam);
}

view();

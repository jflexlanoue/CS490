<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("instructor");

if (isset($_POST['delete'])) {
    $idx["id"] = $_POST['id'];
    util::ForwardDeleteRequest("question.php", $idx);
}

if (isset($_POST['question'])) {
    if ($_POST['question'] == "") {
        $view["message"] = 'Question cannot be left blank.';
        $view["model"] = $_POST; // Repopulate inputs
    } else if($_POST['answer'] == "") {
        $view["message"] = 'Answer cannot be left blank.';
        $view["model"] = $_POST; // Repopulate inputs
    } else if($_POST["id"] == "") {
        $response = util::ForwardPostRequest("question.php", $_POST);
        if($response['success']){
            $view["message"] = 'Question and answer added successfully.';
        } else{
            $view["message"] = $response['error'];
        }
    } else {
        $response = util::ForwardPatchRequest("question.php", $_POST);
        if($response['success']){
            $view["message"] = 'Question and answer added successfully.';
        } else{
            $view["message"] = $response['error'];
        }
    }
}

$view["title"] = "Create Question";
if (isset($_GET['edit'])) {
    $view["editing"] = true;
    $view["title"] = "Edit Question";
    $view["model"] = util::ForwardGetRequest("question.php", array("id" => $_GET["edit"]))["result"];
}

$QuestionBank = util::ForwardGETRequest("question.php", array());

if(!$QuestionBank['success']) {
    $view["message"] = $QuestionBank['error'];
}

$view["itemarray"] = $QuestionBank['result'];

view();
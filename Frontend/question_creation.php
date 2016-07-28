<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("instructor");

if (isset($_POST['question'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    if ($question == "") {
        $view["message"] = 'Question cannot be left blank.';
    } else if ($answer == "") {
        $view["message"] = 'Answer cannot be left blank.';
    } else {
        $response = util::ForwardPostRequest("question.php", $_POST);
        if($response['success']){
            $view["message"] = 'Question and answer added successfully.';
        } else{
            $view["message"] = $response['error'];
        }
    }
}

if (isset($_GET['edit'])) {
    $editing = true;
    $model["id"] = $_GET['edit'];
    $model =$response = util::ForwardGetRequest("question.php", $model);
}

$QuestionBank = util::ForwardGETRequest("question.php", array());

if(!$QuestionBank['success']) {
    $view["message"] = $QuestionBank['error'];
}

$view["itemarray"] = $QuestionBank['result'];

view();
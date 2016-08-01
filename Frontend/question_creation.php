<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("instructor");

if (isset($_POST['delete'])) {
    $idx["id"] = $_POST['id'];
    util::ForwardDeleteRequest("question.php", $idx);
}

if (isset($_POST['question'])) {

    $testcaseindex = $_POST["testcaseindex"];

    $testcases = array();
    for($i = 0; $i <= (int)$testcaseindex; $i++) {
        if(!isset($_POST["testcase" . $i]))
            continue;
        $testcases[] = ($_POST["testcase" . $i]);
        unset($_POST["testcase" . $i]);
    }

    $_POST["testcases"] = implode(";", $testcases);
    $_POST["properties"] = $_POST["constraint"];

    if ($_POST['question'] == "") {
        $view["message"] = 'Question cannot be left blank.';
        $view["model"] = $_POST; // Repopulate inputs
    } else if($_POST['answer'] == "") {
        $view["message"] = 'Answer cannot be left blank.';
        $view["model"] = $_POST; // Repopulate inputs
    } else if($_POST["id"] == "") {
        // TODO: Don't pass POST directly
        $response = util::ForwardPostRequest("question.php", $_POST);
        if($response['success']){
            $view["message"] = 'Question and answer added successfully.';
        } else{
            $view["message"] = $response['error'];
        }
    } else {
        // TODO: Don't pass POST directly
        $response = util::ForwardPatchRequest("question.php", $_POST);
        if($response['success']){
            $view["message"] = 'Question and answer added successfully.';
        } else{
            $view["message"] = $response['error'];
        }
    }
}

if (isset($_GET['edit'])) {
    $view["editing"] = true;
    $view["title"] = "Edit Question";
    $view["model"] = util::ForwardGetRequest("question.php", array("id" => $_GET["edit"]))["result"];
    $view["testcases"] = explode(';', json_decode($view["model"]["testcase_json"]));
    $view["testcase_index"] = count($view["testcases"]) - 1;
    $view["constraint"] = $view["model"]["properties"][0];
} else {
    $view["title"] = "Create Question";
    $view["testcase_index"] = 0;
}

$QuestionBank = util::ForwardGETRequest("question.php", array());

if(!$QuestionBank['success']) {
    $view["message"] = $QuestionBank['error'];
}

$view["itemarray"] = $QuestionBank['result'];

view();
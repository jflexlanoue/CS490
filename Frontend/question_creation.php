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

$question_html = '
<tr>
    <td><input type="checkbox" name="{{id}}"></td>
    <td><a class="button" href="question_creation.php?edit={{id}}">Edit</a></td>
    <td>{{id}}</td>
    <td>{{question}}</td>
    <td>{{answer}}</td>
    <td>{{points}}</td>
</tr>';

$items = "";
foreach ( $QuestionBank['result'] as $q ){
    $question = array();
    $question["id"] = $q['id'];
    $question["question"] = util::Printable($q['question']);
    $question["answer"] = util::Printable($q['answer']);
    $question["points"] = util::Printable($q['points']);
    $items .= render($question_html, $question);
}

$view["items"] = $items;
view();
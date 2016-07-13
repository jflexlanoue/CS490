<?php
require 'common.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if (isset($_REQUEST["id"])) {
            $response["result"] = load_or_error('question', $_REQUEST["id"]);
        } else {
            $response["result"] = R::findAll('question');
        }
        break;

    case "POST":
        must_be_instructor();
        verify_params(['question', 'answer']);

        $question = R::dispense('question');
        $question->question = $_REQUEST["question"];
        $question->answer = $_REQUEST["answer"];

        $response["result"] = R::store($question);
        break;

    case "PATCH":
        must_be_instructor();
        verify_params(['id']);

        $question = load_or_error('question', $_REQUEST["id"]);

        if (isset($_REQUEST["question"]))
            $question->question = $_REQUEST["question"];
        if (isset($_REQUEST["answer"]))
            $question->answer = $_REQUEST["answer"];
        R::store($question);
        break;

    case "DELETE":
        must_be_instructor();
        verify_params(['id']);

        $question = load_or_error('question', $_REQUEST["id"]);
        R::trash($question);
        break;
}
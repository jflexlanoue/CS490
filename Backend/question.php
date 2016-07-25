<?php
require 'common.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if (isset($_REQUEST["id"])) {
            $response["result"] = load_or_error('question', $_REQUEST["id"]);
        } else {
/*
            - minScore [optional]
            - maxScore [optional]
            - search [optional]
            - orderby [optional] // Can be "name", "score"
            - order [optional] // Can be asc, desc
  */

            $response["result"] = R::findAll('question');
        }
        break;

    case "POST":
        must_be_instructor();
        verify_params(['question', 'answer']);

        $question = R::dispense('question');
        $question->question = $_REQUEST["question"];
        $question->answer = $_REQUEST["answer"];
        $question->points = $_REQUEST["points"];

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
        if (isset($_REQUEST["points"]))
            $question->points = $_REQUEST["points"];
        R::store($question);
        break;

    case "DELETE":
        must_be_instructor();
        verify_params(['id']);

        $question = load_or_error('question', $_REQUEST["id"]);
        R::trash($question);
        break;
}
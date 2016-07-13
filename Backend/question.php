<?php
require 'common.php';

switch($_SERVER['REQUEST_METHOD'])
{
    case "GET":
        if(isset($_REQUEST["id"]))
        {
            $response["result"] = R::load('question', $_REQUEST["id"]);

            if($response["result"]["id"] == 0)
            {
                unset($response["result"]);
                Error("Question not found");
            }
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

        $question = R::load('question', $_REQUEST["id"]);

        if($question["id"] == 0) {
            Error("Question not found");
        }

        if(isset($_REQUEST["question"]))
            $question->question = $_REQUEST["question"];
        if(isset($_REQUEST["answer"]))
            $question->answer = $_REQUEST["answer"];
        R::store($question);
        break;

    case "DELETE":
        must_be_instructor();
        verify_params(['id']);

        $question = R::load('question', $_REQUEST["id"]);

        if($question["id"] == 0) {
            Error("Question not found");
        }
        R::trash($question);
        break;
}
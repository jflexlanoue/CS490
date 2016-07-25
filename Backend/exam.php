<?php
require 'common.php';

// TODO: https://github.com/jflexlanoue/CS490/issues/13

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if (isset($_REQUEST["id"])) {
            $response["result"] = load_or_error('exam', $_REQUEST["id"]);

            // Expand questions
            $response["result"]->sharedQuestionList;
        } else {
            $response["result"] = R::findAll('exam');

            // Expand questions
            foreach($response["result"] as $exam) {
                $exam->sharedQuestionList;
            }
        }
        break;

    case "POST":
        verify_params(['title', 'released']);

        $exam = R::dispense('exam');
        $exam->title = $_REQUEST["title"];
        $exam->released = filter_var($_REQUEST["released"], FILTER_VALIDATE_BOOLEAN);
        $exam->sharedQuestionList = array();

        if (isset($_REQUEST["questionIDs"])) {
            $questions = explode(",", $_REQUEST["questionIDs"]);
            foreach($questions as $question) {
                array_push($exam->sharedQuestionList, load_or_error('question', (int)$question));
            }
        }

        $response["result"] = R::store($exam);
        break;

    case "PATCH":
        verify_params(['id']);

        $exam = load_or_error('exam', $_REQUEST["id"]);

        if (isset($_REQUEST["title"]))
            $exam->title = $_REQUEST["title"];
        if (isset($_REQUEST["released"]))
            $exam->released = filter_var($_REQUEST["released"], FILTER_VALIDATE_BOOLEAN);

        if (isset($_REQUEST["questionIDs"])) {
            $exam->sharedQuestionList = array();
            if($_REQUEST["questionIDs"] != -1) {
                $questions = explode(",", $_REQUEST["questionIDs"]);
                foreach ($questions as $question) {
                    array_push($exam->sharedQuestionList, load_or_error('question', (int)$question));
                }
            }
        }
        R::store($exam);
        break;

    case "DELETE":
        verify_params(['id']);

        $exam = load_or_error('exam', $_REQUEST["id"]);
        R::trash($exam);
        break;
}
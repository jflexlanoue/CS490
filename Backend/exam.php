<?php
require 'common.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if (isset($_REQUEST["id"])) {
            $response["result"] = load_or_error('exam', $_REQUEST["id"]);

            // Expand questions
            $questions = $response["result"]->xownExamquestionList;
            foreach($questions  as $question) {
                $question->question;
            }
        } else {
            $response["result"] = R::findAll('exam');

            // Expand questions
            foreach($response["result"] as $exam) {
                $questions = $exam->xownExamquestionList;
                foreach($questions  as $question) {
                    $question->question;
                }
            }
        }
        break;

    case "POST":
        verify_params(['title', 'released']);

        $exam = R::dispense('exam');
        $exam->title = $_REQUEST["title"];
        $exam->released = filter_var($_REQUEST["released"], FILTER_VALIDATE_BOOLEAN);
        $exam->xownExamquestionList = array();

        if(isset($_REQUEST["questionIDs"]) && $_REQUEST["questionIDs"] != -1) {
            if (isset($_REQUEST["questionIDs"])) {
                $questions = explode(",", $_REQUEST["questionIDs"]);
                foreach ($questions as $question) {
                    $q = explode(":", $question);
                    $id = $q[0];
                    $points = -1;
                    if(count($q) > 1)
                        $points = $q[1];
                    $obj = R::dispense('examquestion');
                    $obj["question"] = load_or_error('question', (int)$id);
                    $obj["points"] = (int)$points;
                    $exam->xownExamquestionList[] = $obj;
                }
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
            $exam->xownExamquestionList = array();
            if($_REQUEST["questionIDs"] != -1) {
                if (isset($_REQUEST["questionIDs"])) {
                    $questions = explode(",", $_REQUEST["questionIDs"]);
                    foreach ($questions as $question) {
                        $q = explode(":", $question);
                        $id = $q[0];
                        $points = -1;
                        if(count($q) > 1)
                            $points = $q[1];
                        $obj = R::dispense('examquestion');
                        $obj["question"] = load_or_error('question', (int)$id);
                        $obj["points"] = (int)$points;
                        $exam->xownExamquestionList[] = $obj;
                    }
                }
            }
        }
        R::store($exam);
        break;

    case "DELETE":
        verify_params(['id']);

        $exam = load_or_error('exam', $_REQUEST["id"]);

        $results = R::find('result', "exam_id=:examid", array("examid" => $_REQUEST["id"]));

        foreach ($results as $result) {
            R::trash($result);
        }

        R::trash($exam);
        break;
}
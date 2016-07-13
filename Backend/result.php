<?php
require 'common.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if (isset($_REQUEST["id"])) {
            // Return single result
            $response["result"] = load_or_error('result', $_REQUEST["id"]);
            exit();
        }

        if (isset($_REQUEST["studentID"])) {
            // All results for studentID
            $response["result"] = R::find('result', "student_id=:id", [":id" => $_REQUEST["studentID"]]);
        } else if (isset($_REQUEST["questionID"])) {
            // All results for questionID
            $response["result"] = R::find('result', "question_id=:id", [":id" => $_REQUEST["questionID"]]);
        } else if (isset($_REQUEST["examID"])) {
            // All results for examID
            $response["result"] = R::find('result', "exam_id=:id", [":id" => $_REQUEST["examID"]]);
        } else {
            $response["result"] = R::findAll('result');
            //Error("Requires one of: id, studentID, questionID, or examID");
        }

        // Common error handling for studentID, questionID, and examID
        if (!count($response["result"])) {
            unset($response["result"]);
            Error("No results found");
        }

        // Expand linked fields
        if (isset($_REQUEST["expand"]) && filter_var($_REQUEST["expand"], FILTER_VALIDATE_BOOLEAN)) {
            foreach($response["result"] as $item) {
                $user = load_or_error('user', $item->student_id);
                $item->student = scrub_user($user);
                $item->exam;
                $item->question;
            }
        }
        break;

    case "POST":
        verify_params(['studentID', 'examID', 'questionID', 'score', 'studentAnswer', 'feedback']);

        $result = R::dispense('result');
        $result->student = load_or_error('user', $_REQUEST["studentID"]);
        $result->exam = load_or_error('exam', $_REQUEST["examID"]);
        $result->question = load_or_error('question', $_REQUEST["questionID"]);
        $result->score = $_REQUEST["score"];
        $result->studentAnswer = $_REQUEST["studentAnswer"];
        $result->feedback = $_REQUEST["feedback"];

        $response["result"] = R::store($result);
        break;

    case "PATCH":
        verify_params(['id']);
        $result = load_or_error('result', $_REQUEST["id"]);

        if (isset($_REQUEST["studentID"]))
            $result->student = load_or_error('user', $_REQUEST["studentID"]);
        if (isset($_REQUEST["examID"]))
            $result->exam = load_or_error('exam', $_REQUEST["examID"]);
        if (isset($_REQUEST["questionID"]))
            $result->question = load_or_error('question', $_REQUEST["questionID"]);
        if (isset($_REQUEST["score"]))
            $result->score = $_REQUEST["score"];
        if (isset($_REQUEST["studentAnswer"]))
            $result->studentAnswer = $_REQUEST["studentAnswer"];
        if (isset($_REQUEST["feedback"]))
            $result->feedback = $_REQUEST["feedback"];
        R::store($result);
        break;

    case "DELETE":
        verify_params(['id']);
        $result = load_or_error('result', $_REQUEST["id"]);
        R::trash($result);
        break;
}
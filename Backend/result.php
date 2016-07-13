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
            $response["result"] = R::find('user', "studentID=:id", [":id" => $_REQUEST["studentID"]]);
        } else if (isset($_REQUEST["questionID"])) {
            // All results for questionID
            $response["result"] = R::find('user', "questionID=:id", [":id" => $_REQUEST["questionID"]]);
        } else if (isset($_REQUEST["examID"])) {
            // All results for examID
            $response["result"] = R::find('user', "examID=:id", [":id" => $_REQUEST["examID"]]);
        } else {
            Error("Requires one of: id, studentID, questionID, or examID");
        }

        if (!count($response["result"])) {
            unset($response["result"]);
            Error("No results found");
        }
        break;

    case "POST":
        verify_params(['studentID', 'examID', 'questionID', 'score', 'studentAnswer', 'feedback']);

        $result = R::dispense('result');
        $result->studentID = $_REQUEST["studentID"];
        $result->examID = $_REQUEST["examID"];
        $result->questionID = $_REQUEST["questionID"];
        $result->score = $_REQUEST["score"];
        $result->studentAnswer = $_REQUEST["studentAnswer"];
        $result->feedback = $_REQUEST["feedback"];

        $response["result"] = R::store($result);
        break;

    case "PATCH":
        verify_params(['id']);
        $result = load_or_error('result', $_REQUEST["id"]);

        $result->score = $_REQUEST["score"];
        $result->studentAnswer = $_REQUEST["studentAnswer"];
        $result->feedback = $_REQUEST["feedback"];

        if (isset($_REQUEST["studentID"]))
            $result->studentID = $_REQUEST["studentID"];
        if (isset($_REQUEST["examID"]))
            $result->examID = $_REQUEST["examID"];
        if (isset($_REQUEST["questionID"]))
            $result->questionID = $_REQUEST["questionID"];
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

        if ($result["id"] == 0) {
            Error("result not found");
        }
        R::trash($result);
        break;
}
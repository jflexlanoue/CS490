<?php
require 'common.php';

must_be_instructor();

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if (isset($_REQUEST["id"])) {
            $response["result"] = load_or_error('exam', $_REQUEST["id"]);
        } else {
            $response["result"] = R::findAll('exam');
        }
        break;

    case "POST":
        verify_params(['title', 'released']);

        $exam = R::dispense('exam');
        $exam->title = $_REQUEST["title"];
        $exam->released = $_REQUEST["released"];

        $exam->questionIDs = array();
        if (isset($_REQUEST["questionIDs"]))
            $exam->questionIDs = $_REQUEST["questionIDs"]; // TODO: parse IDs, verify questions exist
        $response["result"] = R::store($exam);
        break;

    case "PATCH":
        verify_params(['id']);

        $exam = load_or_error('exam', $_REQUEST["id"]);

        if (isset($_REQUEST["title"]))
            $exam->title = $_REQUEST["title"];
        if (isset($_REQUEST["released"]))
            $exam->released = $_REQUEST["released"];
        if (isset($_REQUEST["questionIDs"]))
            $exam->questionIDs = $_REQUEST["questionIDs"]; // TODO: parse IDs, verify questions exist
        R::store($exam);
        break;

    case "DELETE":
        verify_params(['id']);

        $exam = load_or_error('exam', $_REQUEST["id"]);
        R::trash($exam);
        break;
}
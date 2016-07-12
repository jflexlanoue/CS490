<?php
require 'common.php';

must_be_instructor();

switch($_SERVER['REQUEST_METHOD'])
{
    case "GET":
        if(isset($_REQUEST["id"]))
        {
            $response["result"] = R::load('exam', $_REQUEST["id"]);

            if($response["result"]["id"] == 0)
            {
                unset($response["result"]);
                Error("Exam not found");
            }
        } else {
            $response["result"] = R::findAll('exam');
        }
        break;

    case "POST":
        verify_params(['id', 'released']);

        $exam = R::dispense('exam');
        $exam->title = $_REQUEST["title"];
        $exam->released = $_REQUEST["released"];

        if(isset($_REQUEST["questionIDs"]))
            $exam->questionIDs = $_REQUEST["questionIDs"]; // TODO
        $response["result"] = R::store($exam);
        break;

    case "PATCH":
        verify_params(['id']);

        $exam = R::load('exam', $_REQUEST["id"]);

        if($exam["id"] == 0) {
            Error("Exam not found");
        }

        if(isset($_REQUEST["title"]))
            $exam->title = $_REQUEST["title"];
        if(isset($_REQUEST["released"]))
            $exam->released = $_REQUEST["released"];
        if(isset($_REQUEST["questionIDs"]))
            $exam->questionIDs = $_REQUEST["questionIDs"]; // TODO
        R::store($exam);
        break;

    case "DELETE":
        verify_params(['id']);

        $exam = R::load('exam', $_REQUEST["id"]);

        if($exam["id"] == 0) {
            Error("Exam not found");
        }
        R::trash($exam);
        break;
}
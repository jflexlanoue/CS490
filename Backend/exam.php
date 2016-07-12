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
                Error("No exams found");
            }
        } else {
            $response["result"] = R::findAll('exam');
        }
        break;
    case "POST":
        $exam = R::dispense('exam');
        $exam->title = $_REQUEST["title"];
        $exam->released = $_REQUEST["released"];

        if(isset($_REQUEST["questionIDs"]))
            $exam->questionIDs = $_REQUEST["questionIDs"]; // TODO
        R::store($exam);
        break;
    case "PATCH":
        break;
    case "DELETE":
        break;
}
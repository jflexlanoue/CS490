<?php
require 'common.php';

function AddToQuery(&$query, &$querystr, $str, $operation = "=") {
    if (isset($_REQUEST[$str])) {
        $query[":" . $str] = $_REQUEST[$str];
        AddAndPad($querystr, $str . " " . $operation . " :" . $str);
    }
}

function StripAnswers(&$obj) {
    $keys = array();
    foreach($obj as $result => $val) {
        array_push($keys, $result);
    }
    foreach ($keys as $key) {
        if(isset($obj[$key]->answer))
            unset($obj[$key]["answer"]);
    }
}

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

            $querystr = "";
            $query = array();
            AddToQuery($query, $querystr, "minScore");
            AddToQuery($query, $querystr, "maxScore");
            AddToQuery($query, $querystr, "search", "LIKE");
            AddToQuery($query, $querystr, "orderby", "OrderBy");

            if(count($query) == 0) {
                $response["result"] = R::findAll('question');
            } else {
                $response["result"] = R::find('question', $querystr, $query);
            }

            if(!is_instructor()) {
                StripAnswers($response["result"]);
                $response["answersHidden"] = "true";
            }

            if (!count($response["result"])) {
                unset($response["result"]);
                Error("No results found");
            }
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
<?php
require 'common.php';

function AddToQuery(&$query, &$querystr, $field, $str, $operation = "=", $replacestr = "") {
    if (isset($_REQUEST[$str])) {
        if(empty($replacestr)) {
            $replacestr = $str;
        }
        $query[":" . $str] = $replacestr;
        AddAndPad($querystr, $field . " " . $operation . " :" . $str);
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
            AddToQuery($query, $querystr, "points", "minPoints", ">");
            AddToQuery($query, $querystr, "points", "maxPoints", "<");
            AddToQuery($query, $querystr, "question", "search", "like", "%" . $_REQUEST["search"] . "%");
            if (isset($_REQUEST["orderby"])) {
                $querystr .= " order by " . $_REQUEST["orderby"];
                if (isset($_REQUEST["order"])) {
                    $querystr .= " " . $_REQUEST["order"];
                }
            }

            $response["querystr"] = $querystr;
            $response["query"] = $query;

            if(empty($querystr)) {
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
        verify_params(['question', 'answer', 'points']);

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
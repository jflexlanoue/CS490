<?php
require 'common.php';

function AddToQuery(&$query, &$querystr, $field, $str, $operation = "=", $replacestr = "") {
    if (isset($_REQUEST[$str])) {
        if(empty($replacestr)) {
            $replacestr = $_REQUEST[$str];
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

            // Force lazy loaded item to load
            scrub_question($response["result"]);
        } else {
            $querystr = "";
            $query = array();
            AddToQuery($query, $querystr, "points", "minPoints", ">=");
            AddToQuery($query, $querystr, "points", "maxPoints", "<=");
            AddToQuery($query, $querystr, "question", "search", "like", "%" . (isset($_REQUEST["search"])?$_REQUEST["search"]:"") . "%");
            if (isset($_REQUEST["orderby"])) {
                $querystr .= " order by " . $_REQUEST["orderby"];
                if (isset($_REQUEST["order"])) {
                    $querystr .= " " . $_REQUEST["order"];
                }
            }

            if(empty($querystr)) {
                $response["result"] = R::findAll('question');
            } else {
                $response["result"] = R::find('question', $querystr, $query);
            }

            // Force lazy loaded item to load
            foreach($response["result"] as $q) {
                scrub_question($q);
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
        $question->testcases = $_REQUEST["testcases"];

        $enum = array();
        $props = explode(",", $_REQUEST["properties"]);
        foreach($props as $prop)
            array_push($enum, R::enum('properties:' . $prop));
        $question->sharedPropertiesList = $enum;

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
        if (isset($_REQUEST["testcases"]))
            $question->testcases = $_REQUEST["testcases"];
        if (isset($_REQUEST["properties"])) {
            $enum = array();
            $props = explode(",", $_REQUEST["properties"]);
            foreach($props as $prop)
                array_push($enum, R::enum('properties:' . $prop));
            $question->sharedPropertiesList = $enum;
        }

        R::store($question);
        break;

    case "DELETE":
        must_be_instructor();
        verify_params(['id']);

        $question = load_or_error('question', $_REQUEST["id"]);
        R::trash($question);
        break;
}
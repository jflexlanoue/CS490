<?php
/*
Creates database connection, sets up minimal 'API framework'
*/

# API setup
$response = array();
$response["success"] = true;
session_start();
header('Content-Type: application/json');

$instructor_override = false;
if(isset($_REQUEST["APIKEY"]) && $_REQUEST["APIKEY"] == "kX7mMZkMOvSYOCWyjI6jPUEtGuquNsKBPHkY0UR6M56N2iLtk3U4Qrb4G20HWIH")
{
    $instructor_override = true;
}

# Reports failure reason an exits
function Error($msg)
{
    global $response;
    $response["success"] = false;
    $response["error"] = $msg;
    exit();
}

# Handles API response and cleanup
function Shutdown()
{
    global $response;

    try {
        R::close();
    } catch (Exception $e) {
        // Not reported in "error" field because there may be another error there already
        // Also if the call succeeded, we don't really care about this
        $response["devError"] = "Failed to close database";
    }

    // API response
    echo(json_encode($response));
}

register_shutdown_function('Shutdown');

# Reports uncaught exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    Error("Error " . $errno . ': ' . $errstr . "\r"
        . 'Line ' . $errline . ': ' . $errfile);
}

set_error_handler("exception_error_handler");

if (isset($_REQUEST["method"]))
{
    $_SERVER['REQUEST_METHOD'] = strtoupper($_REQUEST["method"]);
}

# REST methods
$_DELETE = array();
$_PATCH = array();
if ($_SERVER['REQUEST_METHOD'] == "PATCH" || $_SERVER['REQUEST_METHOD'] == "DELETE") {
    try {
        $body = '';
        $handle = fopen('php://input', 'r');
        while (!feof($handle)) {
            $body .= fread($handle, 1024);
        }

        if ($_SERVER['REQUEST_METHOD'] == "PATCH") {
            parse_str($body, $_PATCH);
            $_REQUEST = array_merge($_REQUEST, $_PATCH);
        } else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
            parse_str($body, $_DELETE);
            $_REQUEST = array_merge($_REQUEST, $_DELETE);
        }
    } catch (Exception $e2) {
        $response["exception_object"] =  $e2;
        Error("Error reading request body");
    }
}

# Don't allow script to be loaded directly
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404); # Meh, this doesn't work
    Error('This file cannot be accessed directly');
}

# DB connection (via RedBeanPHP)
require "dependencies/rb.php";
R::setup('mysql:host=sql2.njit.edu;dbname=glh4', 'glh4', file_get_contents('./db.auth'));
if (!R::testConnection()) {
    Error("Could not connect to database");
}

# Utility function
function is_instructor()
{
    global $instructor_override;
    if($instructor_override) // Set by API key
        return true;

    if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"] ||
        !isset($_SESSION["permission"]) || $_SESSION["permission"] != "instructor") {
        return false;
    }
    return true;
}

function must_be_instructor()
{
    if (!is_instructor()) {
        Error("Method can only be called by an instructor");
    }
}

function verify_params($params)
{
    foreach ($params as $param) {
        if (!isset($_REQUEST[$param])) {
            Error("Missing parameter: " . $param);
        }
    }
}

function load_or_error($table, $id, $message = "") {
    $return = R::load($table, $id);

    if ($return["id"] == 0) {
        if($message != "") {
            Error($message);
        }
        Error("ID " . $id . " not found in " . $table . " table");
    }
    return $return;
}

// Scrub user object FOR DISPLAY ONLY, DO NOT PERSIST THIS
function scrub_user(&$user){
    // Convert permission from database enum to a plain string
    $permission = strtolower($user->permission->name);
    unset($user->permission_id);
    unset($user->permission);
    $user->permission = $permission;

    // Don't expose hash
    unset($user->hash);
    return $user;
}

// Scrub question object FOR DISPLAY ONLY, DO NOT PERSIST THIS
function scrub_question(&$question){
    // Convert permission from database enum to a plain string
    $properties = array();

    foreach($question->sharedProperties as $prop) {
        array_push($properties, strtolower($prop->name));
    }
    unset($question->sharedProperties);
    $question->properties = $properties;

    // Expand test cases

    if(isset($question->testcases)) {
        $cases = array();
        $tc = explode(";", $question->testcases);
        foreach ($tc as $c)
            array_push($cases, explode(",", $c));
        $question->testcases = $cases;
    }

    return $question;
}

function AddAndPad(&$str, $it) {
    if(!empty($str))
        $str .= " AND ";
    $str .= $it;
}
<?php
/*
Creates database connection, sets up minimal 'API framework'
*/

# API setup
$response = array();
$response["success"] = true;
session_start();
header('Content-Type: application/json');

# REST methods
$_DELETE = array();
$_PATCH = array();
if($_SERVER['REQUEST_METHOD'] == "DELETE")
{
    str_parse(file_get_contents('php://input'), $_DELETE);
    $_REQUEST = array_merge($_REQUEST, $_DELETE);
}
if($_SERVER['REQUEST_METHOD'] == "PATCH")
{
    str_parse(file_get_contents('php://input'), $_PATCH);
    $_REQUEST = array_merge($_REQUEST, $_PATCH);
}

# Reports failure reason an exits
function Error($msg)
{
    global $response;
    $response["success"] = false;
    $response["error"] = $msg;
    exit();
}

# Don't allow script to be loaded directly
if(basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]))
{
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); # Meh, this doesn't work
    Error('This file cannot be accessed directly');
}

# DB connection (via RedBeanPHP)
require "dependencies/rb.php";
R::setup('mysql:host=sql2.njit.edu;dbname=glh4', 'glh4', file_get_contents('./db.auth'));
if(!R::testConnection())
    Error("Could not connect to database");

# Handles API response and cleanup
function Shutdown()
{
    global $response;

    try
    {
        R::close();
    } catch (Exception $e)
    {
        // Not reported in "error" field because there may be another error there already
        // Also if the call succeeded, we don't really care about this
        $response["devError"] = "Failed to close database";
    }

    // API response
    echo(json_encode($response));
}

register_shutdown_function('Shutdown');

# Reports uncaught exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    Error("Error " . $errno . ': ' . $errstr . "\r"
        . 'Line ' . $errline . ': ' . $errfile);
}

set_error_handler("exception_error_handler");

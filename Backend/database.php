<?php
/*
Creates database connection, sets up minimal 'API framework'
*/

# API setup
$response = array();
header('Content-Type: application/json');

# Don't allow script to be loaded directly
if ( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) 
{
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); # Meh, this doesn't work
    $response['success'] = false;
    $response['error'] = 'This file cannot be accessed directly';
    die(json_encode($response));
}

# DB connection
$db = new mysqli('sql2.njit.edu', 'glh4', file_get_contents('./db.auth'), 'glh4');

if($db->connect_errno > 0){
    $response['success'] = false;
    $response['error'] = 'Unable to connect to database [' . $db->connect_error . ']';
    die(json_encode($response));
}

?>
<?php
/*
Handles backend authentication

Expects:
    POST
        username
        password
    
Returns
    JSON {
        authenticated: boolean
        success: boolean
        error: string, optional
    }
*/

require 'database.php';

$response["authenticated"] = false;

if (!isset($_POST['username']) || !isset($_POST['password']))
    Error("Expected POST with username and password");

$username = $_POST['username'];
$password = $_POST['password'];

# Retrieve hash from database
$ret = $db->query("SELECT username, hash FROM Users WHERE username='" . $username ."'");

# Username not in db
if($ret->num_rows == 0)
    Error("Username not in database");

$row = $ret->fetch_assoc();
$hash = $row['hash'];

if (password_verify($password, $hash)) {
    $response["authenticated"] = true;
}

?>
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

require 'common.php';

$response["authenticated"] = false;

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    Error("Expected POST with username and password");
}

$username = $_POST['username'];
$password = $_POST['password'];

$user = R::findOne('user', " username = ? ", [$username]);

# Username not in db
if (!count($user)) {
    Error("Username not in database");
}

if (password_verify($password, $user->hash)) {
    $response["authenticated"] = true;
    $response["permission"] = strtolower($user->permission->name);

    $_SESSION["authenticated"] = true;
    $_SESSION["username"] = $username;
    $_SESSION["userID"] = $user->id;
    $_SESSION["permission"] = strtolower($user->permission->name);;

    // Generate new session ID (avoids session fixation)
    session_regenerate_id();
}
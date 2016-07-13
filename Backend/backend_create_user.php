<?php
/*
Backend user creation

Expects:
    GET
        username
        password

Returns
    JSON {
        success: boolean
        error: string, optional
    }
*/

require 'common.php';

if (!isset($_REQUEST['username']) ||
    !isset($_REQUEST['password']) ||
    !isset($_REQUEST['permission'])) {
    Error("Expected username, password, and permission parameters");
}

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$permission = $_REQUEST['permission'];

if ($permission != 'student' && $permission != 'instructor') {
    Error("Invalid permission: expected 'student' or 'instructor'");
}

# Username already in db

$duplicates = R::find('user', "username=:username", [":username" => $username]);
if (count($duplicates)) {
    Error("Username already in database");
}

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);

# Store user
$user = R::dispense('user');
$user->username = $username;
$user->hash = $hash;
$user->permission = R::enum('permission:' . $permission);
$id = R::store($user);

$response["success"] = true;
<?php
require 'common.php';

if(!is_instructor()) {
    if ($_SERVER['REQUEST_METHOD'] != "POST" && $_SERVER['REQUEST_METHOD'] != "GET") { // Anyone can create an account and view their own account
        if (!isset($_REQUEST["id"]) || ($_SESSION["userID"] != $_REQUEST["id"])) {
            Error("Students can only modify and view their own accounts");
        }
    }
}

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if(is_instructor()) {
            if (isset($_REQUEST["id"])) {
                $result = load_or_error('user', $_REQUEST["id"]);
                scrub_user($result);
                $response["result"] = $result;
            } else if (isset($_REQUEST["all"])) {
                {
                    $result = R::findAll('user');
                    foreach ($result as $resp) {
                        scrub_user($resp);
                    }
                    $response["result"] = $result;
                }
            }
        }
        else {
            $user = load_or_error('user', $_SESSION["userID"]);
            $response["result"] = json_encode(scrub_user($user));
        }
        break;

    case "POST":
        verify_params(['username', 'password', 'permission']);

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

        # Store user
        $user = R::dispense('user');
        $user->username = $username;
        $user->hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
        $user->permission = R::enum('permission:' . $permission);
        $id = R::store($user);

        $response["result"] = R::store($user);
        break;

    case "PATCH":
        verify_params(['id']);
        $user = load_or_error('user', $_REQUEST["id"]);

        if (isset($_REQUEST["password"])) {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
            $user->hash = $hash;
        }
        if (isset($_REQUEST["permission"])) {
            $permission = $_REQUEST['permission'];
            if ($permission != 'student' && $permission != 'instructor') {
                Error("Invalid permission: expected 'student' or 'instructor'");
            }
            $user->permission = R::enum('permission:' . $permission);
        }
        R::store($user);
        break;

    case "DELETE":
        verify_params(['id']);

        $user = load_or_error('user', $_REQUEST["id"]);
        R::trash($user);
        break;
}
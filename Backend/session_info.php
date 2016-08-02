<?php
require 'common.php';

$response["is_instructor"] = is_instructor();
if (session_status() == PHP_SESSION_ACTIVE) {
    if(!isset($_SESSION["username"])) {
        Error("Invalid session");
    }
    $response["username"] = $_SESSION["username"];
    $response["authenticated"] = $_SESSION["authenticated"];
    $response["permission"] = $_SESSION["permission"];
    $response["session cookie"] = $_COOKIE["PHPSESSID"];
} else {
    Error("No active session");
}
<?php
require 'common.php';

if (session_status() == PHP_SESSION_ACTIVE) {
    if(!isset($_SESSION["username"])) {
        Error("Session active, but session variable is empty");
    }
    $response["username"] = $_SESSION["username"];
    $response["authenticated"] = $_SESSION["authenticated"];
    $response["permission"] = $_SESSION["permission"];
} else {
    Error("No active session");
}
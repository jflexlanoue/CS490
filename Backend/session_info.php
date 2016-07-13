<?php
require 'common.php';

if (session_status() == PHP_SESSION_ACTIVE) {
    $response["username"] = $_SESSION["username"];
    $response["authenticated"] = $_SESSION["authenticated"];
    $response["permission"] = $_SESSION["permission"];
} else {
    Error("No active session");
}
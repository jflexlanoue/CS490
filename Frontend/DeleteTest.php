<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
include("Garyutil.class.php");

$id = 15;

$data = ["id" => $id];

$response = util::ForwardDeleteRequest("question.php", $data);

var_dump($response);

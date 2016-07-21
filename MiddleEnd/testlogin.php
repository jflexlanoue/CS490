<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("util.class.php");

$TripleBR = "<br/><br/><br/>";

echo $TripleBR;

//Login
$LoginPOST = array( "username" => "admin", "password" => "admin");

echo util::ForwardPostRequest("backend_login.php", $LoginPOST);


echo $TripleBR;


echo util::ForwardPostRequest("session_info.php", array());


echo $TripleBR;


echo util::ForwardGetRequest("exam.php", array());



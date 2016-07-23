<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("Garyutil.class.php");

$TripleBR = "<br/><br/><br/>";

echo $TripleBR;

//Login
$LoginPOST = array( "username" => "admin", "password" => "admin");

var_dump(util::ForwardPostRequest("backend_login.php", $LoginPOST));


echo $TripleBR;


var_dump(util::ForwardPostRequest("session_info.php", array()));


echo $TripleBR;


var_dump(util::ForwardGetRequest("exam.php", array()));


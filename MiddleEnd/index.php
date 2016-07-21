<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("util.class.php");

$PageRequest = "";


if(isset($_GET['p'])){
    $PageRequest = $_GET['p'];
} else{
    echo "error";
    return;
}

if(count($_POST) > 0){
   echo utilForwardPostRequest($PageRequest, $_POST);
   return;
}

if(count($_GET) > 1){
   echo util::ForwardGetRequest($PageRequest, $_GET);
   return;
}

echo "error";

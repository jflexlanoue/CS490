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

$data = array_merge($_GET, $_POST);


if(isset($data['method'])){
  echo util::ForwardRequest($PageRequest, $data);
} else{
    
    if(count($_POST) > 0){
        echo util::ForwardPostRequest($PageRequest, $data);
    }
    else{
        echo util::ForwardGetRequest($PageRequest, $data);
    }
}


 


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
include("Garyutil.class.php");

$redirectToLogin = false;


if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
    $redirectToLogin = true;
}




/* tested
  
    $id = 28;
    $data = ["id" => $id];
    $response = util::ForwardDeleteRequest("question.php", $data);

*/
header('Location: question_creation.php');    
?>
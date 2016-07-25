<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
include("Garyutil.class.php");
util::VerifyRole("instructor");

echo json_encode($_POST);

foreach($_POST as $id => $value)
{
    if($value == "on")
    {
        $idx["id"] = $id;
        util::ForwardDeleteRequest("question.php", $idx);
    }
}

/* tested
  
    $id = 28;
    $data = ["id" => $id];
    $response = util::ForwardDeleteRequest("question.php", $data);

*/
header('Location: question_creation.php');
?>
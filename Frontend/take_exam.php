<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole('student');

if (!isset($_GET['id']))
{
    util::Redirect('exam_list.php');
}

$id["id"] = $_GET['id'];

$response = util::ForwardGetRequest("exam.php", $id);
if($response["success"] == false)
    die("Error: " . $response["error"]);

$response["result"]["questioncount"] = count($response["result"]["ownExamquestion"]);
$view["exam"] = $response["result"];

view();
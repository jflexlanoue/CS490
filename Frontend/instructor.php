<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("instructor");

if(isset($_POST["action"])) {
    if($_POST["action"] === "release") {
        $id = $_POST["release"];
        $response = util::ForwardPatchRequest('exam.php', Array("id" => $id, "released" => true));
        if($response["success"] == false)
            die($response["error"]); // TODO pretty error handling - GH
    }
    util::Redirect('instructor.php'); // Make browser do a GET. I think? This should make refreshing the page not give a resend confirmation - GH
}

$exams = util::ForwardGetRequest("exam.php");
$view["exams"] = $exams["result"];
view();

<?php
include("Util/Garyutil.class.php");
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
hdr('Location: question_creation.php');
?>
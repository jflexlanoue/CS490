<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

if(util::IsInstructor())
    $template = "instructor_template";


$response = util::ForwardGETRequest("result.php");
$results['id'] = $_GET["id"];
$exam = array();

foreach ( $response['result'] as $q ){

	if ($q['id'] == $results['id']){
    	$exam['score'] = $q['score'];
		$exam['feedback'] = $q['feedback'];
		$exam['id'] = $q['id'];
	break;
    }
		
}

$view["exam"] = $exam;

if(util::IsInstructor())
    view("feedback_edit");
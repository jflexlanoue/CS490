<?php

$question = $_POST["question"];
$answer = $_POST["answer"];

if ($question == "")
	echo 'Question cannot be left blank.';
else if ($answer == "")
	echo 'Answer cannot be left blank.';
else{
	$_POST = ['question' => $question,
	'answer' => $answer];
	$ch = curl_init('https://web.njit.edu/~glh4/question.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
	$response = curl_exec($ch);
	curl_close($ch);
	$response = json_decode($response, true);
	echo 'Question successfully added.';
}
?>
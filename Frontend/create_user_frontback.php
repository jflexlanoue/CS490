<?php

$username = $_POST["username"];
$password = $_POST["password"];

if ($username == "")
	echo 'Username cannot be left blank.';
else if ($password == "")
	echo 'Password cannot be left blank.';
else{
	$_POST = ['username' => $username,
	'password' => $password];
	$ch = curl_init('https://web.njit.edu/~glh4/user.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
	$response = curl_exec($ch);
	curl_close($ch);
	$response = json_decode($response, true);
	echo 'Account successfully added.';
}

?>
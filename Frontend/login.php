<?php

$username = $_POST["username"];
$password = $_POST["password"];
$_POST = ['username' => $username,
'password' => $password];
$ch = curl_init('https://web.njit.edu/~glh4/backend_login.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response, true);

if ($response['authenticated'] && $response[permission]=="instructor")
	echo 'instructor';
else if ($response['authenticated'] && $response[permission]=="student")
	echo 'student';
?>
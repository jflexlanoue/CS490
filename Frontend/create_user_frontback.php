<?php

$username = $_POST["username"];
$password = $_POST["password"];
$permission = $_POST["permission"];
// $password2 = $_POST["password2"];  // support later

if ($username === "")
	echo 'Username cannot be left blank.';
else if ($password === "")
	echo 'Password cannot be left blank.';
//else if ($password !== $password2) {
//	echo 'Passwords do not match';
else{
	$_POST = ['username' => $username,
			  'password' => $password, 
			  'permission'=> $permission];        
        
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
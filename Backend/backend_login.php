<?php
/*
Handles backend authentication

Expects:
    POST
        username
        password
    
Returns
    JSON {
        authenticated: boolean
        success: boolean
        error: string, optional
    }
*/

include('database.php');

if (isset($_POST['username']) && isset($_POST['password']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    # Retrieve hash from database
    $ret = $db->query("SELECT username, hash FROM Users WHERE username='" . $username ."'");

    # Username not in db
    if($ret->num_rows == 0)
    {
        $response["authenticated"] = false;
        $response["success"] = false;
        $response["error"] = "Username not in database";
    }
    else
    {
        $row = $ret->fetch_assoc();
        $hash = $row['hash'];

        if (password_verify($password, $hash)) {
            $response["success"] = true;
            $response["authenticated"] = true;
        } else {
            $response["success"] = true;
            $response["authenticated"] = false;
        }
    }
}
else
{
    $response["authenticated"] = false;
    $response["success"] = false;
    $response["error"] = "Expected POST with username and password";
}

echo(json_encode($response));

?>
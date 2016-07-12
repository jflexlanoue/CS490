<?php
/*
Backend user creation

Expects:
    GET
        username
        password

Returns
    JSON {
        success: boolean
        error: string, optional
    }
*/


/*
Users table:

CREATE TABLE Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    hash VARCHAR(255) NOT NULL
)
*/

include('database.php');

if (isset($_GET['username']) && isset($_GET['password']))
{
    $username = $_GET['username'];
    $password = $_GET['password'];

    # Username already in db
    $ret = $db->query("SELECT * FROM Users WHERE username='".$username."'");
    if($ret->num_rows > 0)
    {
        $response["success"] = false;
        $response["error"] = "Username already in database";
    }
    else
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);

        # Store user
        $ret = $db->query("INSERT INTO Users (username, hash) VALUES('".$username."', '".$hash."')");

        if($ret)
        {
            $response["success"] = true;
        }
        else
        {
            $response["success"] = false;
            $response["error"] = "Error saving user to database: " . $db->error;
        }
    }
}
else
{
    $response["success"] = false;
    $response["error"] = "Expected GET with username and password";
}

echo(json_encode($response));

?>
<?php
include("Garyutil.class.php");
include("htmlutil.php");

$failedLogin = false;

if(isset($_GET['logout'])){
    $_SESSION = array();
    session_destroy();
}

if (isset($_POST["username"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $response = util::ForwardPostRequest("backend_login.php", $_POST);
    print_r($response);

    if ($response['authenticated']) {
        $_SESSION['role'] = $response["permission"];
    } else{
        $failedLogin = true;
    }
}

if (isset($_SESSION['role'])) {
    if($_SESSION['role'] == "instructor"){
        util::Redirect('instructor.php');
    } else if($_SESSION['role'] == "student") {
        util::Redirect('student.php');
    }
}

hdr("CS 490", false);
?>

    <center>
        <h1>CS 490</h1>
        <h2>Login</h2>

        <form action="index.php" method="POST">
            <input id="username" type="text" name="username" placeholder="username" autofocus><br><br>
            <input id= "password" type="password" name="password" placeholder="password"><br><br>
            <input name="loginButton" type="submit" value="Login"><br><br>
            <a href="create_user.php">Create an account</a><br><br>
    
            <div><span style="color: red;">
               <?php
               if($failedLogin){
                   echo "The username and password you entered do not match.";
                   print_r($response);
               }
               ?>
            </span>
            </div><br>
        </form>

    </center>

<?php
footer();
?>
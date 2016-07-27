<?php
// Index controller

include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

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

if($failedLogin){
    $view["message"] = '
        <article class="message is-danger">
            <div class="message-header">
                Login Failed
            </div>
            <div class="message-body">
                The username and password you entered do not match.
            </div>
        </article>';
}

view();
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
    <section class="hero is-primary is-medium">
        <!-- Hero content: will be in the middle -->
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    Exam System
                </h1>
                <h2 class="subtitle">
                    Login
                </h2>
            </div>
        </div>
    </section>

    <div class="section">
    <div class="container">

        <?php
        if($failedLogin){
            echo '
                    <article class="message is-danger">
                      <div class="message-header">
                         Login Failed
                      </div>
                      <div class="message-body">
                          The username and password you entered do not match.
                      </div>
                    </article>';
        }
        ?>

        <form action="index.php" method="POST">
            <input id="username" type="text" name="username" placeholder="username" autofocus><br><br>
            <input id= "password" type="password" name="password" placeholder="password"><br><br>
            <input class="button is-primary" name="loginButton" type="submit" value="Login"><br><br>
            <a class="button" href="create_user.php">Create an account</a><br><br>
        </form>

    </div>
    </div>
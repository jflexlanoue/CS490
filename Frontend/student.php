<?php
session_start();
include("Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    $redirectToLogin = true;
}
?>

<html>
    <head>
        <title>Student</title>
    </head>

    <script>
<?php
if ($redirectToLogin) {
    echo "window.location = 'index.php';";
}
?>

function ajax_get(url, callback) {
                var xhr;
                if(typeof XMLHttpRequest !== 'undefined') 
                    xhr = new XMLHttpRequest();
                else {
                var versions = ["Microsoft.XmlHttp",    // Support for older Internet Explorer versions (versions older than IE7)...
                                "MSXML2.XmlHttp",
                                "MSXML2.XmlHttp.3.0",
                                "MSXML2.XmlHttp.4.0",
                                "MSXML2.XmlHttp.5.0"];
                    for(var i = 0; i < versions.length; i++){
                        try {
                            xhr = new ActiveXObject(versions[i]);
                            break;
                        }
                        catch(e){}
                    }
                }
                xhr.onreadystatechange = function(){
                    if((xhr.status !== 200) || (xhr.readyState < 4)) 
                        return;
                    callback(xhr);
                }; 
                xhr.open('get', url, true);
                xhr.send();   
            }

    </script>

    <body>
        <a href="edit_user.php" style="float:right">Account settings</a><br>
        <a href="index.php?logout=1" style="float:right">Logout</a>

    <center>
        <h1>Student</h1>

        <a href="results.php">Graded Questions</a><br>
    </center>

</body>
</html>






<?php
session_start();
include("Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
    $redirectToLogin = true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>CS 490 - Instructor</title>
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

            ajax_get('https://web.njit.edu/~glh4/exam.php', function(xhr){
                    var response = xhr.responseText;       
                    var list = document.getElementById('list');
                    list.innerHTML = '';                                    // Clear list; otherwise, updated list appends to older list--needs testing
                    for (var i = 0 ; i < response.length; i++) {
                        list.innerHTML += '<li>' + response[i] + '</li>';
                        // list.innerHTML += '<li>'+ 'test' + '</li>'; 
                    }      
                });

        </script>
    </head>
    <body>
        <a href="edit_user.php" style="float:right">Account settings</a><br>
        <a href="index.php?logout=1" style="float:right">Logout</a>
    <center>
        <h1>Instructor</h1>
        <a href="question_creation.php">Question Creation</a><br>
        <a href="exam_creation.php">New Exam</a><br>
        <h1>Published Exams</h1>
        <div id="list"></div>
        <br>
    </center>

</body>
</html>








<html>
    <head>
        <title></title>
    </head>
    <body>

    </body>
</html>
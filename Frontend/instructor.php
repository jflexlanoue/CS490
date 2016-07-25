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
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function(){
                    if((xhr.status !== 200) || (xhr.readyState < 4)) 
                        return;
                    callback(xhr);
                }; 
                xhr.open('get', url, true);
                xhr.send();   
            }

            ajax_get('https://web.njit.edu/~jl366/?p=exam.php', function(xhr){
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
        <a href="question_creation.php">Question Bank</a><br>
        <a href="exam_creation.php">New Exam</a><br>
        <h1>Published Exams</h1>
        <div id="list">
            <?php

            // TODO List exams

            ?>
        </div>
        <br>
    </center>

</body>
</html>
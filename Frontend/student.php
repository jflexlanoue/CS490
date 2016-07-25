<?php
session_start();
include("Garyutil.class.php");

util::VerifyRole("student");
?>

<html>
    <head>
        <title>Student</title>
    </head>

    <script>

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

        <a href="take_exam.php">Take Exam</a><br>
        <a href="results.php">Graded Questions</a><br>

         <?php 

        $examList = util::ForwardGETRequest("question.php", array());
        if( !$examList['success'])
                    
                    echo 'error';

         echo 'test';
         echo $examList[0]['title'];
         echo $examList[1]['id'];
         echo $examList[2]['questionIDs'];
         echo $examList[1]['released'];
         ?>

         <table>
                <tr>
                    <td><strong>ID</strong></td>
                </tr>


        <?php

        foreach ( $examList['title'] as $q ){ 

                        echo '<tr>';
                       // echo '<td><input type="checkbox" name="'.$q['id'].'"></td>'; 
                       // echo '<td><input type="button" value="Edit" onclick="deleteRow('.$id.');"></td>';        
                        echo '<td>' . $q['title'] . '</td>';
                       // echo '<td>' . $q['question'] . '</td>'; 
                       // echo '<td>' . $q['answer'] . '</td>';
                        echo '</tr>';
                    }
                    // echo '<input type="button" value="Delete 2" id="btnTest">';
                   //  echo '</form>';
                    $id++;
        
        ?>
    </table>
    </center>

</body>
</html>






<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    
    session_start();
    include("Garyutil.class.php");
    
    $redirectToLogin = false;
    
    
    if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
        $redirectToLogin = true;
    }
    
    $message = "";
    
    if (isset($_POST['question'])) {
        
        $question = $_POST['question'];
        $answer = $_POST['answer'];
    
        if ($question == "") {
            $message = 'Question cannot be left blank.';
        } else if ($answer == "") {
            $message = 'Answer cannot be left blank.';
        } else {
    
            $response = util::ForwardPostRequest("question.php", $_POST);
            
            if($response['success']){
                $message = 'Question and answer added successfully.';
                
            } else{
                
                $message = $response['result'];
            }
        }
    }
    
    ?>
<html>
    <head>
        <title>Question Creation</title>
        <script>
            <?php
                if ($redirectToLogin) {
                
                    echo "window.location = 'index.php';";
                }
                ?>
                

            function getSelectedBox(checkForm) {
                // JavaScript & jQuery Course - http://coursesweb.net/javascript/
                var selectedBox = [];        // array that will store the value of selected checkboxes
            
                // gets all the input tags in checkForm, and their number
                var inputFields = checkForm.getElementsByTagName('input');
            
                // traverse the inputFields elements, and adds the value of selected (checked) checkbox in selectedBox
                for(var i=0; i<inputFields.length; i++) {
                if(inputFields[i].type == 'checkbox' && inputFields[i].checked == true) 
                    selectedBox.push(inputFields[i].value);
                }
            
                return selectedBox;
            }
            
            
                    function ajax_post(url, callback) {
                        var xhr;
            
            /*
                        var checkResults = 'Checkboxes: ';
                        for (var i = 0; i < document.checkForm.elements.length; i++){
                            if (checkForm.elements[i].type == 'checkbox'){
                                if (checkForm.elements[i].checked == true){
                                    checkResults += checkForm.elements[i].value + ' ';
                                }
                            }
                        }
            */            
            
                        if(typeof XMLHttpRequest !== 'undefined') 
                            xhr = new XMLHttpRequest();
                        else {
                            var versions = ["Microsoft.XmlHttp",    // Support for older Internet Explorer versions (older than IE7)...
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
                        xhr.open('post', url, true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.send("selectedBox="+selectedBox);
                        document.getElementById("message").innerHTML = ".................";         // indication of processing        
                    }             
                    
            
                    
        </script>
    </head>
    <body>
        <a href="instructor.php" style="float:left">Return to main page</a>
        <a href="edit_user.php" style="float:right">Account settings</a><br>
        <a href="index.php?logout=1" style="float:right">Logout</a>
        <center>
            <h1>Question Creation</h1>
            <br>
            <div id="message"> 
                <?php
                    if ($message != "") {
                        echo $message;
                    }
                    ?>
            </div>
            <br>
            <form action = "question_creation.php" method="POST">
                <label for="question">Question</label><br>
                <textarea name="question" value="question" rows="8" cols="40"  placeholder="Enter question..." autofocus></textarea><br><br>
                <label for="answer">Answer</label><br>
                <textarea name = "answer" value="answer" rows="8" cols="40"  placeholder="Enter answer..."></textarea><br><br>
                <input type = "submit" name="btn" value="Add" ><br><br>
            </form>
            <h2>Questions/Answers Bank</h2>
            <?php 
                $QuestionBank = util::ForwardGETRequest("question.php", array());
                
                if( !$QuestionBank['success']) {
                    
                    echo 'error';
                    //return;
                }
                    ?>
            <table>
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>ID</strong></td>
                    <td><strong>Question</strong></td>
                    <td><strong>Answer</strong></td>
                </tr>
                <?php
                    echo '<form name="checkForm">';
                    $id = 1;
                    foreach ( $QuestionBank['result'] as $q ){ 
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="'.$q['id'].'"></td>'; 
                        echo '<td><input type="button" value="Edit" onclick="deleteRow('.$id.');"></td>';        
                        echo '<td>' . $q['id'] . '</td>';
                        echo '<td>' . $q['question'] . '</td>'; 
                        echo '<td>' . $q['answer'] . '</td>';
                        echo '</tr>';
                    }
                    echo '<input type="button" value="Delete 2" id="btnTest">';
                    echo '</form>';
                    $id++;
                    ?>
            </table>
            <input type="submit" name="delete" value="Delete" onclick="ajax_post('question_deletion.php', function(xhr){
              
                document.getElementById('message').innerHTML = 'response';
                })">
            <script>
                document.getElementById('btnTest').onclick = function(){
                var selected = getSelectedBox(this.form);     // gets the array returned by getSelectedBox()
                alert(selected);      // debug
                }
            </script>
            <?php
                /*   in question_deletion.php
                            $id = 31;
                            $data = ["id" => $id];
                            $response = util::ForwardDeleteRequest("question.php", $data);
                */
                ?>
            <!-- <input type = "button" name="btn" value="Test delete" onclick="function delete()"><br><br> -->
            <div id="message"></div>
        </center>
    </body>
</html>
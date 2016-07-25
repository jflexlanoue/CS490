<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    
    session_start();
    include("Garyutil.class.php");

    util::VerifyRole("instructor");
    
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
        <script src="site.js"></script>
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

            <form name="checkForm" action="question_deletion.php" method="post">
            <table>
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>ID</strong></td>
                    <td><strong>Question</strong></td>
                    <td><strong>Answer</strong></td>
                </tr>
                <?php
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
                    $id++;
                    ?>
            </table>
            <input type="submit" name="delete" value="Delete"">
            </form>

            <script>
                document.getElementById('btnTest').onclick = function(){
                var selected = getSelectedBox(this.form);
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
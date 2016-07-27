<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("Garyutil.class.php");
include("htmlutil.php");

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

$template = "instructor_template";
hdr("Question Creation");
?>

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

            <style type="text/css">
                tr, td {border: 1px solid black; }
                tr.noBorder td {border: 0; }

            </style>

            <table>
                <tr class ="noBorder">
                    <td></td>
                    <td><strong><center>ID</center></strong></td>
                    <td><strong><center>Question</center></strong></td>
                    <td><strong><center>Answer</center></strong></td>
                </tr>
                <?php

                $question_html = '
<tr>
    <td><input type="checkbox" name="{{id}}"></td>
    <td>{{id}}</td>
    <td>{{question}}</td>
    <td>{{answer}}</td>
</tr>';

                foreach ( $QuestionBank['result'] as $q ){
                    $question = array();
                    $question["id"] = $q['id'];
                    $question["question"] = util::Printable($q['question']);
                    $question["answer"] = util::Printable($q['answer']);

                    echo render($question_html, $question);
                }
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
        <div id="message"></div>
    </center>

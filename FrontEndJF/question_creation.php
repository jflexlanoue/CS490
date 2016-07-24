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
            $message = 'Question/Answer Added Succesfully';
            
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
        </script>
    </head>
    <body>

        <a href="instructor.php">Return to main page</a><br>
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
        <form action = "question_creation.php" method="POST">


            <label for="question">Question</label><br>
            <textarea name="question" value="question" rows="10" cols="60"  placeholder="Enter question..." autofocus></textarea><br><br>
            <label for="answer">Answer</label><br>
            
            
            <textarea name = "answer" value="answer" rows="10" cols="60"  placeholder="Enter answer..."></textarea><br><br>
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
                <td>id</td>
                <td>Question</td>
                <td>Answer</td>
                </tr>
                <?php
            
            foreach ( $QuestionBank['result'] as $q ){           
                   echo '<tr>'; 
                echo '<td>' . $q['id'] . '</td>';
                    echo '<td>' . $q['question'] . '</td>';
                     echo '<td>' . $q['answer'] . '</td>';
                     echo '</tr>';
            }
        ?>
        </table>

    </center>
</body>
</html>
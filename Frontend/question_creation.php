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
        </div><br>
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
                <td>id</td>
                <td>Question</td>
                <td>Answer</td>
                </tr>
                <?php
            $i = 1;
            foreach ( $QuestionBank['result'] as $q ){       // $q is an array    
                echo '<tr>';
                echo '<td><input type="button" value="Delete" onclick="deleteRow('.$i.')"></td>';
                echo '<td>' . $q['id'] . '</td>';
                echo '<td>' . $q['question'] . '</td>'; 
                echo '<td>' . $q['answer'] . '</td>';
                echo '</tr>';
                $i++;
            }
        ?>
        </table>

    </center>
</body>
</html>
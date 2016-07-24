<?php
session_start();
include("Garyutil.class.php");

$redirectToLogin = false;

if (!isset($_SESSION['role']) || $_SESSION['role'] != "instructor") {
    $redirectToLogin = true;
}
?>



<html>
    <head>
        <title>Exam Creation</title>
    </head>
    <body>
        <a href="instructor.php">Return to main page</a>
        <a href="edit_user.php" style="float:right">Account settings</a><br>
        <a href="index.php?logout=1" style="float:right">Logout</a><br></br>

    <center>
    <h2>Create Exams</h2>

    	<?php 
            
        $questionRetrieval = util::ForwardGETRequest("question.php", array());
        
        if( !$questionRetrieval['success']) {
            
            echo 'error';
            //return;
        }
            ?>
        
        <table>
            <tr>
                <!-- <td></td> -->
                <td>id</td>
                <td>Question</td>
                <td>Answer</td>
                </tr>
                <?php
            $i = 1;
            foreach ( $questionRetrieval['result'] as $q ){       // $q is an array    
                echo '<tr>';
                // echo '<input type="checkbox" name="check_list[]" value="PHP">';
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

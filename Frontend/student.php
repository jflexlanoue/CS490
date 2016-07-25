<?php
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole("student");
hdr("Student");
?>

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

<?php
footer();
?>
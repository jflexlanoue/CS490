<?php
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole("instructor");
hdr("Results");
?>

        <style>
            th, td {
            border: 1px solid black;
        }           
        </style>

        <table>
            <tr>
                <!-- <td></td> -->
                <td>Question ID</td>
                <td>Score</td>
                <td>Student Answer</td>
                <td>Feedback</td>
                </tr>
        <?php $resultsRetrieval = util::ForwardGETRequest("result.php", array());

        
            $i = 1;
            foreach ( $resultsRetrieval['result'] as $q ){       // $q is an array    
                echo '<tr>';
                // echo '<input type="checkbox" name="check_list[]" value="PHP">';
                echo '<td>' . $q['question_id'] . '</td>';
                echo '<td>' . $q['score'] . '</td>';
                echo '<td>' . $q['student_answer'] . '</td>'; 
                echo '<td>' . $q['feedback'] . '</td>';
                echo '</tr>';
                $i++;
            }
        ?>
        </table>

<?php
footer();
?>
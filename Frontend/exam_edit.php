<?php
include("Garyutil.class.php");
include("htmlutil.php");
util::VerifyRole('instructor');
hdr("Exam Creation");
?>

<a href="instructor.php">Return to main page</a>

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

<?php
footer();
?>
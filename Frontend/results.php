<?php
include("Garyutil.class.php");
include("htmlutil.php");

hdr("Results");

function question_by_id($id) {
    global $questioncache;
    if(!isset($questioncache[$id])) {
        $questioncache[$id] = util::ForwardGETRequest("question.php", array("id" => $id))["result"];
    }
    return $questioncache[$id];
}

function exam_by_id($id) {
    global $examcache;
    if(!isset($examcache[$id])) {
        $examcache[$id] = util::ForwardGETRequest("exam.php", array("id" => $id))["result"];
    }
    return $examcache[$id];
}
?>

    <a href="student.php" style="float:left">Return to main page</a>

        <style>
            th, td {
            border: 1px solid black;
        }           
        </style>

        <table>
            <tr>
                <td>Exam</td>
                <td>Question</td>
                <td>Student Answer</td>
                <td>Score</td>
                <td>Feedback</td>
                </tr>
        <?php
        $id = array();
        if(!util::IsInstructor())
            $id = array("studentID" => util::GetUserID());
        $resultsRetrieval = util::ForwardGETRequest("result.php", $id);

            foreach ( $resultsRetrieval['result'] as $q ){       // $q is an array
                if(util::IsInstructor() || exam_by_id($q["exam_id"])["released"] == 1) {
                    echo '<tr>';
                    echo '<td>' . exam_by_id($q['exam_id'])["title"] . '</td>';
                    echo '<td>' . question_by_id($q['question_id'])["question"] . '</td>';
                    echo '<td>' . $q['student_answer'] . '</td>';
                    echo '<td>' . $q['score'] . '</td>';
                    echo '<td>' . $q['feedback'] . '</td>';
                    echo '</tr>';
                }
                else{
                    echo '<tr>';
                    echo '<td>' . exam_by_id($q['exam_id'])["title"] . '</td>';
                    echo '<td>' . question_by_id($q['question_id'])["question"] . '</td>';
                    echo '<td>' . $q['student_answer'] . '</td>';
                    echo '<td>N/A</td>';
                    echo '<td>Exam not released</td>';
                    echo '</tr>';
                }
            }
        ?>
        </table>

<?php
footer();
?>
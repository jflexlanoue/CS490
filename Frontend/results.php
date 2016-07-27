<?php
include("Garyutil.class.php");
include("htmlutil.php");

$template = "instructor_template";
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

        $result_html = '
<tr>
    <td>{{exam_title}}</td>
    <td>{{question}}</td>
    <td>{{student_answer}}</td>
    <td>{{score}}</td>
    <td>{{feedback}}</td>
</tr>';

            foreach ( $resultsRetrieval['result'] as $q ) {
                $item = array();

                $item["exam_title"] = exam_by_id($q['exam_id'])["title"];
                $item["question"] = util::Printable(question_by_id($q['question_id'])["question"]);
                $item["student_answer"] = util::Printable($q['student_answer']);

                if(util::IsInstructor() || exam_by_id($q["exam_id"])["released"] == 1) {
                    $item["score"] = $q['score'];
                    $item["feedback"] = util::Printable($q['feedback']);
                } else{
                    $item["score"] = "N/A";
                    $item["feedback"] = "Exam not released";
                }

                echo render($result_html, $item);
            }
        ?>
        </table>

<?php footer(); ?>
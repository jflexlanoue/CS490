<?php
include("Garyutil.class.php");
include("htmlutil.php");

if(util::IsInstructor())
    $template = "instructor_template";
else
    $template = "student_template";
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


        <?php
        $id = array();
        if(!util::IsInstructor())
            $id = array("studentID" => util::GetUserID());
        $resultsRetrieval = util::ForwardGETRequest("result.php", $id);

        $result_html = '
<div class="card is-fullwidth">
  <header class="card-header">
    <p class="card-header-title">
      {{exam_title}}
    </p>
    <a class="card-header-icon" style="{{instructor_only}}">Edit</a>
  </header>
  <div class="card-content">
    <div class="content">
      <article class="message is-primary">
        <div class="message-header">
          Question
        </div>
        <div class="message-body">
        {{question}}
        </div>
      </article>
      <article class="message">
        <div class="message-header">
          Student Answer
        </div>
        <div class="message-body">
        {{student_answer}}
        </div>
      </article>
      <article class="message">
        <div class="message-header">
          Feedback
        </div>
        <div class="message-body">
        Score: {{score}}
        <br>
        {{feedback}}
        </div>
      </article>
    </div>
  </div>
</div>';

            foreach ( $resultsRetrieval['result'] as $q ) {
                $item = array();

                $item["exam_title"] = exam_by_id($q['exam_id'])["title"];
                $item["question"] = util::Printable(question_by_id($q['question_id'])["question"]);
                $item["student_answer"] = util::Printable($q['student_answer']);
                $item["instructor_only"] = util::IsInstructor() ? "" : "display:none";

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
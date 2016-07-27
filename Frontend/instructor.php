<?php
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole("instructor");

$template = "instructor_template";
hdr("CS 490 - Instructor", true);

if(isset($_POST["action"])) {
    if($_POST["action"] === "release") {
        $id = $_POST["release"];
        $response = util::ForwardPatchRequest('exam.php', Array("id" => $id, "released" => true));
        if($response["success"] == false)
            die($response["error"]); // TODO pretty error handling - GH
        util::Redirect('instructor.php'); // Make browser to a GET. I think? This should make refreshing the page not give a resend confirmation - GH
    }
}

?>
<nav class="panel">
    <p class="panel-heading">
        Exams
    </p>
    <p class="panel-tabs">
        <a class="is-active" href="#">All</a>
        <a href="#">Released</a>
        <a href="#">Unreleased</a>
        <a href="#">In-Progress</a>
    </p>

    <?php
    function print_exam($exam)
    {
        $exam_html = '<a class="panel-block is-active" href="exam_edit.php?id={{exam_id}}">
                      <span class="panel-icon">
                        <i class="fa fa-book"></i>
                      </span>
                          {{exam_name}}
                      </a>';

        echo render($exam_html, array(
            "exam_id" => $exam["id"],
            "exam_name" => $exam["title"],
            "question_count" => count($exam["sharedQuestion"]),
            "exam_released" => ($exam["released"] ==1 ? "Released" : "Not released")));
    }

    $exams = util::ForwardGetRequest("exam.php");
    foreach ($exams["result"] as $exam)
    {
        echo print_exam($exam);
    }

    ?>
</nav>
<?php
footer();
?>
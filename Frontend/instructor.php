<?php
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole("instructor");
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

    <center>
        <h1>Instructor</h1>
        <a href="question_creation.php">Question Bank</a><br>
        <a href="exam_creation.php">New Exam</a><br>
        <h1>Exams</h1>
        <div id="list">
            <?php
            
            function print_exam($exam)
            {
                echo "<div class='exam'>";
                    echo "<div>ID: " . $exam["id"] . "</div>";
                    echo "<div>Title: " . $exam["title"] . "</div>";
                    echo "<div>" . ($exam["released"] ==1 ? "Released" : "Not released") . "</div>";
                    echo "<div>Questions: " . count($exam["sharedQuestion"]) . "</div>";
                    echo '<form action="" method="post"><input type="hidden" name="action" value="release"><button value="' . $exam["id"] . '" name="release">Release Scores</button></form>';
                echo "</div>";
            }

            $exams = util::ForwardGetRequest("exam.php");
            foreach ($exams["result"] as $exam)
            {
                echo print_exam($exam);
            }

            ?>
        </div>
        <br>
    </center>
<?php
footer();
?>
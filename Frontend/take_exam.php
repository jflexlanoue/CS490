<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole('student');

$message = "";

if (!isset($_GET['id']))
{
    util::Redirect('exam_list.php');
}

$id["id"] = $_GET['id'];

hdr("Take Exam");
$response = util::ForwardGetRequest("exam.php", $id);
if($response["success"] == false)
    die("Error: " . $response["error"]);
$response = $response["result"];
?>
<a href="student.php" style="float:left">Return to main page</a>
<div style="text-align: center;">
    <h1>Exam: <?php echo $response["title"] ?></h1>
    Questions: <?php echo count($response["sharedQuestion"]) ?>

    <form action = "submit_results.php" method="POST">
        <input type="hidden" name="examid" value="<?php echo $id["id"] ?>">

        <?php
        foreach($response["sharedQuestion"] as $question) {
          echo '
            <label for="question">Question</label>
            <div class="test_question">' .
                util::Printable($question["question"])
            .'</div>
            <label for="answer">Answer</label>
            <textarea name="answer'.$question["id"].'" rows="8" cols="40"  placeholder="Enter answer..."></textarea>'; // TODO don't leak question IDs
        }
        ?>

        <input type="submit" value="Submit" >
    </form>

</div>
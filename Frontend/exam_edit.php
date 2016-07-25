<?php
include("Garyutil.class.php");
include("htmlutil.php");
util::VerifyRole('instructor');
hdr("Exam Creation");

$exam_id = $_GET["id"];
$creation = !isset($exam_id);

// Do edit
if($_SERVER['REQUEST_METHOD'] === "POST") {
    print_r($_POST);

    //util::Redirect('instructor.php');
}

if(!$creation) {
    $exam = util::ForwardGETRequest("exam.php", array("id" => $exam_id));
    if(!$exam['success']) {
        die($exam["error"]);
    }
    $exam = $exam["result"];
    $exam_question_ids = array();
    foreach ($exam["sharedQuestion"] as $item => $value) {
        array_push($exam_question_ids, $value["id"]);
    }
} else {
    $exam = array();
    $exam["title"] = "";
    $exam["released"] = "";
    $exam["sharedQuestion"] = array();
}

$questionRetrieval = util::ForwardGETRequest("question.php", array());

if( !$questionRetrieval['success']) {
    die($questionRetrieval["error"]);
}
$questions = $questionRetrieval["result"];
?>

<a href="instructor.php">Return to main page</a>

<center>
    <h2><?php echo ($creation ? "Create Exams" : "Edit exam") ?></h2>

    <form method="post">
        <?php if($creation) echo '<input type="hidden" name="examid" value="' . $exam_id . '">' ?>
        <div>
            <label>Name</label>
            <input name="name" value="<?php echo $exam["title"] ?>">
        </div>
        <div>
            <label>Released</label>
            <input name="released" <?php if($exam["released"] == 1) echo 'checked="checked"'?> type="checkbox">
        </div>

        <table>
            <tr>
                <td>Included</td>
                <td>Question</td>
                <td>Answer</td>
            </tr>
            <?php
            foreach ($questions as $q) {
                echo '<tr>';
                echo '<td><input name="' . $q['id'] . '" type="checkbox" ' . (in_array($q['id'], $exam_question_ids) ? 'checked="checked"' : "") . '></td>';
                echo '<td>' . $q['question'] . '</td>';
                echo '<td>' . $q['answer'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <div>
            <button type="submit"><?php echo ($creation ? "Create" : "Edit") ?></button>
        </div>
    </form>
</center>

<?php
footer();
?>
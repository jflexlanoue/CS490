<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");
util::VerifyRole('instructor');

$template = "instructor_template";
hdr("Exam Creation");

if(isset($_GET["id"]))
    $exam_id = $_GET["id"];
$creation = !isset($exam_id);

// Handle response
if($_SERVER['REQUEST_METHOD'] === "POST") {
    // Handle deletion
    if(isset($_POST["Delete"]) && $_POST["Delete"] == "Delete") {
        $res = util::ForwardDeleteRequest("exam.php", Array("id" => $_GET["id"]));
        if(!$res['success']) {
            die($res["error"]);
        }
        util::Redirect('instructor.php');
    }

    // Handle creation/editing
    $exam = array();
    if(!$creation)
        $exam["id"] = $_POST["examid"];

    $exam["title"] = $_POST["name"];
    $exam["released"] = isset($_POST["released"]);
    $exam["questionIDs"] = array();

    // TODO Edit point overrides
    foreach ($_POST as $key => $value) {
        if(substr($key, 0, 14) === "sharedquestion" && $value === "on") {
            $question_id = (int)substr($key, 14);
            array_push($exam["questionIDs"], $question_id);
        }
    }

    if(count($exam["questionIDs"]) == 0) {
        $exam["questionIDs"] = "-1";
    } else {
        $exam["questionIDs"] = implode(",", $exam["questionIDs"]);
    }

    if($creation)
        $res = util::ForwardPostRequest("exam.php", $exam);
    else
        $res = util::ForwardPatchRequest("exam.php", $exam);

    print_r($exam);

    if(!$res['success']) {
        die($res["error"]);
    }

    util::Redirect('instructor.php');
}

$exam_question_ids = array();
if(!$creation) {
    $exam = util::ForwardGETRequest("exam.php", array("id" => $exam_id));
    if(!$exam['success']) {
        die($exam["error"]);
    }
    $exam = $exam["result"];
    foreach ($exam["ownExamquestion"] as $item => $value) {
        $exam_question_ids[] = $value["question_id"];
        $exam_question_point_overrides[] = $value["points"];  // TODO Display this
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

<center>
    <h2><?php echo ($creation ? "Create Exams" : "Edit exam") ?></h2>
    <div><?php if(!$creation) echo '
        <form method="post">
            <div>
                <button type="submit" name="Delete" value="Delete">Delete</button>
            </div>
        </form>' ?></div>


    <form method="post">
        <?php if(!$creation) echo '<input type="hidden" name="examid" value="' . $exam_id . '">' ?>
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

            $question_html = '
<tr>
    <td><input name="sharedquestion{{id}}" type="checkbox" {{checked}}></td>    
    <td>{{question}}</td>
    <td>{{answer}}</td>
</tr>';

            foreach ($questions as $q) {
                $item = array();
                $item["id"] = $q['id'];
                $item["checked"] = (in_array($q['id'], $exam_question_ids) ? 'checked="checked"' : "");
                $item["question"] = $q['question'];
                $item["answer"] = $q['answer'];

                echo render($question_html, $item);
            }
            ?>
        </table>

        <div>
            <button type="submit"><?php echo ($creation ? "Create" : "Edit") ?></button>
        </div>
    </form>
</center>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(10);


session_start();

include("util.class.php");
include("grading.class.php");

if (isset($_POST["Question"])) {
    $Question = $_POST['Question'];
    $Answer = $_POST['Answer'];
    $StudentAnswer = $_POST['StudentAnswer'];
} else {

    $Question = "Write a child class Car that inherits from Vehicle and overrides the Drive method. Inside the new function, print the distance traveled denoted in miles.

Vechicle.java:

public class Vehicle
{
  public void Drive(int miles)
  {
    // throw not implemented exception
  }
}";

    $Answer = "public class Car extends Vehicle

{

  public void Drive(int miles)

  {

    System.out.println(miles + \" miles\");

  }

}";

    $StudentAnswer = "public class Car extends Vehicle

{

  public void Drive(int miles)

  {

    System.out.println(miles + \" miles\");

  }

}";
}
?>

<form action="" method="POST">
    <br/>Question: <br/>
    <textarea type="text" name = "Question" rows="4" cols="50"><?php echo $Question ?></textarea>
    <br/>Solution:<br/>
    <textarea type="text" name = "Answer" rows="15" cols="50"><?php echo $Answer ?></textarea>
    <br/>Student Answer:<br/>
    <textarea type="text" name = "StudentAnswer" rows="15" cols="50"><?php echo $StudentAnswer ?></textarea>
    <input type="submit" value="submit">
</form>


<?php
$Grade = new grading($Answer, $StudentAnswer);
$Grade->DoGrading();
$FeedBack = $Grade->GetFeedBack();

echo $Grade->ScorePerc;
?>



FeedBack: <br/>
<?php
foreach ($FeedBack as $f) {
    ?>

    <div style="background-color: grey; margin:20px;" >
        <?php echo $f ?>
    </div>

    <?php
}
?>
<?php
$maxIndex = count($Grade->AnswerTokens);


if (count($Grade->SolutionTokens) > $maxIndex) {
    $maxIndex = count($Grade->SolutionTokens);
}
echo $maxIndex;
?>
<table style="width:100%">
    <tr style="">
        <td colspan="2" style="text-align: center;">Solution</td>
        <td colspan="2" style="text-align: center;">Answer</td>
    </tr>
    <tr >
        <td style="border-bottom: black solid 2px">Type</td>
        <td style="border-bottom: black solid 2px">Value</td>
        <td style="border-bottom: black solid 2px">Type</td>
        <td style="border-bottom: black solid 2px">Value</td>
    </tr>
<?php
for ($i = 0; $i < $maxIndex; $i++) {
    $solT = "";
    $solV = "";
    if ($i < count($Grade->SolutionTokens)) {
        $solT = $Grade->SolutionTokens[$i]['type'];
        $solV = $Grade->SolutionTokens[$i]['value'];
    }
    $ansT = "";
    $ansV = "";
    if ($i < count($Grade->AnswerTokens)) {
        $ansT = $Grade->AnswerTokens[$i]['type'];
        $ansV = $Grade->AnswerTokens[$i]['value'];
    }
    ?>
        <tr>
            <td><?php echo $solT; ?></td>
            <td><?php echo $solV; ?></td>
            <td style=""><?php echo $ansV; ?></td>
            <td><?php echo $ansT; ?></td>
        </tr>
    <?php
}
?>
</table>

    <?php ?>

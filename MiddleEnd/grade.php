<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("util.class.php");
include("grading.class.php");


$login = json_decode(util::ForwardPostRequest("backend_login.php", ["username" => "admin" , "password" => "admin"]),1);

$GETPARAMS = array();
    
if(isset($_GET['examid'])){
    $ExamId = $_GET['examid'];
    $GETPARAMS = ["examID" => $ExamId ,
                  "expand" => 1];
}

$Results = json_decode(util::ForwardGETRequest("result.php",$GETPARAMS ),1);

if(!$Results['success']){
    echo "No Results";
    return;
}

$Results = $Results['result'];

foreach($Results as  $v){
    
    $Result_ID = $v['id'];
    
    $sAnswer = $v['student_answer'];
    $sSolution = $v['question']['answer'];
    $sQuestion = $v['question']['question'];
    
    echo "Solution: " . $sSolution;
    
        echo "<br />";
    echo "<br />";
    
        echo "Student Answer: " . $sAnswer;
    
            echo "<br />";
    echo "<br />";
    
    $Grade = new grading($sSolution, $sAnswer);
    $Grade->DoGrading();
    $FeedBack = $Grade->GetFeedBack();

    echo "Score: " . $Grade->ScorePerc;

        echo "<br />";
    echo "<br />";

    
    foreach ($FeedBack as $f) {
        echo $f;
            echo "<br />";
    echo "<br />";
    }
        echo "<br />";
    echo "<br />";
    

    //PATCH  id => $Result_ID , score => $Score , feedback => $feedback
}


/*
$exam = json_decode(util::ForwardGETRequest("exam.php",$GETPARAMS ),1);

$QuestionPairs = $exam['result']['sharedQuestion'];


foreach($QuestionPairs as $k => $v){
    
    $QuestionId = $v['id'];
    $QuestionQ = $v['question'];
    $QuestionA = $v['answer'];
    

    echo  $QuestionId . " " . $QuestionQ . " " . $QuestionA ;
    echo "<br />";
    echo "<br />";
    
}

$GETPARAMS = ["examID" => $ExamId];
$result = json_decode(util::ForwardGETRequest("result.php",$GETPARAMS ),1);

$ExamAnswers = $result;


foreach($ExamAnswers as $k => $v){
    
    //echo  $k . "   " . $v ;
    echo "<br />";
    echo "<br />";
    
}





?>





<?php
//Set Exam Released



/*
$Grade = new grading($Answer, $StudentAnswer);
$Grade->DoGrading();
$FeedBack = $Grade->GetFeedBack();

echo $Grade->ScorePerc;*/



//['studentID', 'examID', 'questionID', 'score', 'studentAnswer', 'feedback'];
?>

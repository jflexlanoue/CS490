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
    $GETPARAMS = ["method" => "get",
                    "examID" => $ExamId ,
                  "expand" => true];
} else{
    echo "Need GET parameter examid";
    return;
}

$Results = json_decode(util::ForwardRequest("result.php",$GETPARAMS ),1);

if(!$Results['success']){
    echo "No Results";
    return;
}

$Results = $Results['result'];

foreach($Results as  $v){
    
    $Result_ID = $v['id'];
    
    $sAnswer = $v['student_answer'];
    
    if(!isset($v['question'])){
        echo "Question Deleted From the Database";
        echo '<br/>';
        continue;
    }
    
    
    $sSolution = $v['question']['answer'];
    $sQuestion = $v['question']['question'];
    
    $Grade = new grading($sSolution, $sAnswer);
    $Grade->DoGrading();
    
    $sFeedback  = $Grade->GetFeedBack();
    $sScore = $Grade->ScorePerc;
    
    //PATCH  id => $Result_ID , score => $Score , feedback => $feedback
    
    $ParamData = [  "method" => "patch",
                    "id" => $Result_ID 
                   ,"score" => $sScore 
                   ,"feedback" => serialize($sFeedback)];
    
    $PatchRes = json_decode(util::ForwardRequest("result.php",$ParamData ),1);
    

}

$DATA = [ 'method' => "PATCH" 
         ,'id' => $ExamId
         ,'released' => true];

$PatchExam = json_decode(util::ForwardRequest("exam.php",$DATA ),1);

echo $PatchExam['success'];
?>

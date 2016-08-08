    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //session_start();

    include("util.class.php");
    include("grading.class.php");


    //$login = json_decode(util::ForwardPostRequest("backend_login.php", ["username" => "admin" , "password" => "admin"]),1);

    $GETPARAMS = array();

    if(isset($_GET['examid'])){
        $ExamId = $_GET['examid'];
        $GETPARAMS = ["method" => "get",
                        "examID" => $ExamId,
                        "expand" => 1,
                        "APIKEY" => "kX7mMZkMOvSYOCWyjI6jPUEtGuquNsKBPHkY0UR6M56N2iLtk3U4Qrb4G20HWIH"];
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
        
        
        $sTestCases = explode(";", $v['question']['testcases']);

        
        if($sTestCases == NULL){
            $sTestCases = [];
        }

        $Grade = new grading($sSolution, $sAnswer);
        $Grade->DoGrading($sTestCases);

        $sFeedback  = $Grade->GetFeedBack();
        $sScore = $Grade->ScorePerc;

        //PATCH  id => $Result_ID , score => $Score , feedback => $feedback

        $ParamData = [  "method" => "patch",
                        "id" => $Result_ID 
                       ,"score" => $sScore 
                       ,"feedback" => json_encode($sFeedback),
                        "APIKEY" => "kX7mMZkMOvSYOCWyjI6jPUEtGuquNsKBPHkY0UR6M56N2iLtk3U4Qrb4G20HWIH"];

        $PatchRes = json_decode(util::ForwardRequest("result.php",$ParamData ),1);

    }

    ?>

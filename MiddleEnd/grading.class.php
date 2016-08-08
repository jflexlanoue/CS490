<?php

// Requires an active session
// session_start();
class grading {

    public $solution = "";
    public $answer = "";
    public $TestCase = [];
    public $SolutionTokens = [];
    public $AnswerTokens = [];
    public $FeedBack = [];
    public $TOKENS = [];
    public $TOKENS_Keywords = [];
    public $Grade_Max = 0;
    public $Result = [];
    public $ScorePerc = 0;

    function __construct($solution, $answer) {

        $this->Init_Language();

        $this->solution = $this::FormatString($solution);
        $this->answer = $this::FormatString($answer);
    }

    private function Init_Language() {

        $this->TOKENS['ARITHMETIC'] = ["+", "-", "*", "/", "%", "++", "--"];

        $this->TOKENS['RELATIONAL'] = ["==", "!=", ">", "<", ">=", "<="];

        $this->TOKENS['LOGICAL'] = [ "&&", "||", "!", "=", "+="];

        $this->TOKENS['ASSIGNMENT'] = ["=", "+=", "-=", "*=", "/=", "%="];

        $this->TOKENS['SEPARATOR'] = ["{", "}", "(", ")", "[", "]", "\"", ";"];


        $this->TOKENS['KEYWORDS'] = ["abstract", "continue", "for", "new", "switch", "assert", "default", "goto", "package", "synchronized"
            , "boolean", "do", "if", "private", "this", "break", "double", "implements", "protected"
            , "protected", "throw", "byte", "else", "import", "import", "public", "throws", "case", "enum", "instanceof"
            , "return", "transient", "catch", "extends", "int", "short", "try", "char", "final", "interface", "static"
            , "void", "class", "finally", "long", "strictfp", "volatile", "const", "float", "native", "super", "while" , "String"];
    }

    function DoGrading($TestCases) {

        //Simple String Match
        $MatchPerc = $this::Diff($this->solution, $this->answer);

        $this->Result['StringMatching'] = $MatchPerc / 100;
        $this->FeedBack[] = "Answer and Solution Similarity: " . $this->Result['StringMatching'] * 100 . "%";


        //Parse Tokens
        $this->SolutionTokens = $this->ParseTokens($this->solution);
        $this->AnswerTokens = $this->ParseTokens($this->answer);

        $EstMax = $this->GetEstimateTokenScore();

        $this->Result['Completness'] = $EstMax;
        //$this->FeedBack[] = "Estimate of Token Matching: " . $EstMax * 100 . "%";



        $Leven = $this->GetLevenshteinEstimate();

        $this->Result['Levenshetein'] = ( $Leven);
        //$this->FeedBack[] = "Estimated Levenshtein Score: " . $this->Result['Levenshetein'] * 100 . "%";

        $this->TestCase = $TestCases;

        if(count($this->TestCase) == 0){
            $this->FeedBack[] = "This Question does not have any Test Cases";
            
        }
        
        $this->DoCodeGrading();


        $this->CalculateScore();
    }

    function CalculateScore() {
$score = 0;
        
        if($this->Result['compile']){
            $score = 10;
            
            if(!isset($this->Result['run'])){
                $score = 50;
                $score += 50 * $this->Result['StringMatching'];
                $this->ScorePerc = $score;
                return;
            }
            $caseScore = 0;
            foreach($this->Result['run'] as $testRes){
                
                $print = 0;
                $ret = 0;
                
                if(isset($testRes['Print'])){
                    $print = $testRes['Print']; 
                }
                if(isset($testRes['Return'])){
                    $ret = $testRes['Return']; 
                }
                
                if(isset($testRes['Print']) xor isset($testRes['Return']) ){
                    $caseScore += (90 * ($print + $ret)/100);
                } elseif( isset($testRes['Print']) && isset($testRes['Return'])){
                    $caseScore += (45 * ($print)/100);
                    $caseScore += (45 * ($ret)/100);
                } else{
                    
                    $caseScore += 90;
                }
            }

            $caseScore /= count($this->Result['run']);
            
            $score += $caseScore;
            $this->ScorePerc = $score;
            return;
        }
        
        
        if($this->Result['StringMatching'] > .5){
                    //50% For String Matching
            $score = 40 * $this->Result['StringMatching'];
            
        }

        $this->ScorePerc = $score;
    }

    function GetLevenshteinEstimate() {

        $SolString = "";

        foreach ($this->SolutionTokens as $S) {
            $SolString .= substr($S['type'], 2, 1);
        }

        $AnsString = "";

        foreach ($this->AnswerTokens as $A) {
            $AnsString .= substr($A['type'], 2, 1);
        }

        $res = levenshtein($SolString, $AnsString);

        $max = strlen($SolString);

        if (strlen($AnsString) > $max) {
            $max = strlen($AnsString);
        }


        return ($max - $res) / $max;
    }

    function GetKeywordsScore($sol, $ans) {

        //20% for Containing Same amount of Keywords
        //50%
        //50% for containing the keywords in the same order
        //30% for containing the keywords in the same order 


        $KeywordCountSol = 0;

        foreach ($sol as $T) {
            if ($T['type'] == "KEYWORDS") {
                $KeywordCountSol ++;
            }
        }
    }

    function GetEstimateTokenScore() {

        $TokenAns = [];
        $TokenSol = [];

        $SolTokenCount = 0;
        $AnsTokenCount = 0;



        $Multiplier = ['ARITHMETIC' => 4
            , 'RELATIONAL' => 5
            , 'LOGICAL' => 5
            , 'ASSIGNMENT' => 5
            , 'SEPARATOR' => 5
            , 'KEYWORDS' => 5
            , 'IDENTIFIER' => 5];

        foreach ($this->SolutionTokens as $T) {
            if (isset($TokenSol[$T['type']])) {
                $TokenSol[$T['type']] += 1;
            } else {
                $TokenSol[$T['type']] = 1;
            }

            $SolTokenCount += ($Multiplier[$T['type']]);
        }

        foreach ($this->AnswerTokens as $T) {
            if (isset($TokenAns[$T['type']])) {
                $TokenAns[$T['type']] += 1;
            } else {
                $TokenAns[$T['type']] = 1;
            }
            $AnsTokenCount += ($Multiplier[$T['type']]);
        }

        $Score_Missing = 0;

        foreach ($TokenSol as $T => $N) {
            if (!isset($TokenAns[$T])) {

                $Score_Missing += ($N * $Multiplier[$T]);
            } else {
                $Score_Missing += (abs($N - $TokenAns[$T]) * $Multiplier[$T]);
            }
        }

        foreach ($TokenAns as $T => $N) {
            if (!isset($TokenSol[$T])) {

                $Score_Missing += ($N * $Multiplier[$T]);
            }
        }

        $Score_MaxMissing = $SolTokenCount + $AnsTokenCount;

        return ($Score_MaxMissing - $Score_Missing) / $Score_MaxMissing;
    }

    function GetFeedBack() {
        return $this->FeedBack;
    }

    public function ParseTokens($str) {

        
        $str = $this->FormatString($str, true);
        $sTokens = [];
        $str_split = explode(" ", $str);

        foreach ($str_split as $s) {

            $MatchedToken = false;

            foreach ($this->TOKENS as $Type => $Values) {

                if ($MatchedToken) {

                    break;
                }

                if (in_array($s, $Values)) {
                    $MatchedToken = true;
                    $sTokens[] = array("type" => $Type, "value" => $s);
                    break;
                }
            }
            if (!$MatchedToken) {
                if (strlen($s) > 0) {
                    $sTokens[] = array("type" => "IDENTIFIER", "value" => $s);
                }
            }
        }
        return $sTokens;
    }

    public function IsIdentifier($str) {

        //FROM : http://stackoverflow.com/a/5205467
        $Identifier_reg = "([a-zA-Z_$][a-zA-Z\d_$]*\.)*[a-zA-Z_$][a-zA-Z\d_$]*";

        $result = preg_match($Identifier_reg, $str);

        return ($result == 1);
    }

    public function FormatString($str, $SpaceOutTokens = false) {
        /* Replace New Lines with Spaces */
        $str = str_replace("[\n]", " ", $str);
        $str = str_replace("<br />", " ", $str);
        $str = trim($str);

        if ($SpaceOutTokens) {
            foreach ($this->TOKENS as $Type => $Tokens) {
                if ($Type == "KEYWORDS") {
                    continue;
                }
                foreach ($Tokens as $t) {
                    $str = str_replace($t, " " . $t . " ", $str);
                }
            }
        }
        /* Replace multiple Spaces together to just one */
        $str = preg_replace('!\s+!', ' ', $str);

        return $str;
    }

    public function Diff($Solution, $StudentAnswer) {

        $perc = "";
        $diff = similar_text($Solution, $StudentAnswer, $perc);

        return $perc;
    }

    public function DoCodeGrading() {
        

        //Get Solution Function Name and if it returns something.
        $SolFunctionName = "";
        $SolReturns = true;
        foreach($this->SolutionTokens as $tok){
            if($tok['value'] == "void"){
                $SolReturns = false;
            }
            if($tok['type'] == 'IDENTIFIER'){
                $SolFunctionName =  $tok['value'];
                break;
            }
        }

                //Get Solution Function Name and if it returns something.
        $AnsFunctionName = "";
        $AnsReturns = true;
        foreach($this->AnswerTokens as $tok){
            if($tok['value'] == "void"){
                $AnsReturns = false;
            }
            if($tok['type'] == 'IDENTIFIER'){
                $AnsFunctionName =  $tok['value'];
                break;
            }
        }
        
        
        $SolutionResults = $this->ExecGrading($this->solution, $SolFunctionName,$SolReturns );

        $AnswerResults = $this->ExecGrading($this->answer, $AnsFunctionName , $AnsReturns );
        
            $CompileFeedback = "";
            foreach ($AnswerResults['compile'] as $cErr) {
                $CompileFeedback .= str_replace("UPLOADS/ExamGrading/", "", $cErr) . '<br/>';
            }
            
            if($CompileFeedback == ""){
                $this->Result['compile'] = 1;

            } else{
                $this->FeedBack[] = "Compile Error: " . $CompileFeedback;
                $this->Result['compile'] = 0;
            }
            
            
            $CompileFeedback = "";
            foreach ($SolutionResults['compile'] as $cErr) {
                $CompileFeedback .= str_replace("UPLOADS/ExamGrading/", "", $cErr) . '<br/>';
            }
            
            if($CompileFeedback == ""){

            } else{
                $this->FeedBack[] = "Solution Compile Error: " . $CompileFeedback;

            }
            

        $SolRunPrintResults = [];
        $SolRunReturnResults = [];
        $ResBuffer = "";

        foreach($SolutionResults['run'] as $res){
            if(strpos($res, "printstart")){
            } else if (strpos($res, "printend")){
                if($ResBuffer != ""){
                    
                    $SolRunPrintResults[] = $ResBuffer;
                    $ResBuffer = "";
                }
            } else if (strpos($res, "returnstart")){
            } else if (strpos($res, "returnend")){ 
              $SolRunReturnResults[] = $ResBuffer; 
              $ResBuffer = "";
            } else{
                $ResBuffer .= $res;
            }
        }
        
        $AnsRunPrintResults = [];
        $AnsRunReturnResults = [];
        $ResBuffer = "";

        foreach($AnswerResults['run'] as $res){
            if(strpos($res, "printstart")){
            } else if (strpos($res, "printend")){
                $AnsRunPrintResults[] = $ResBuffer;
                $ResBuffer = "";
                
            } else if (strpos($res, "returnstart")){
            } else if (strpos($res, "returnend")){ 
                $AnsRunReturnResults[] = $ResBuffer; 
                $ResBuffer = "";
            } else{
                $ResBuffer .= $res ;
            }
        }
        
        for($i = 0; $i < count($this->TestCase); $i++){
            $TestCaseResult = [];
            
            if(isset($SolRunPrintResults[$i])){
                
                if(isset($AnsRunPrintResults[$i])){
                    if($SolRunPrintResults[$i] == $AnsRunPrintResults[$i]){
                        $TestCaseResult['Print'] = 100;
                         $this->FeedBack[] = "Print Match with Test case " . ($i +1) . " Output: " . $AnsRunPrintResults[$i];
                    } else{
                       
                        $simi = $this->Diff($SolRunPrintResults[$i],$AnsRunPrintResults[$i]);
                        $this->FeedBack[] = "Print mismatch(" . $simi ."%) for Test case " . ($i +1) . " Output: " . $AnsRunPrintResults[$i] . " Expected: " . $SolRunPrintResults[$i] ;
                        
                        $TestCaseResult['Print'] = 50 * ($simi/100);

                    }
                    
                } else{
                    $TestCaseResult['Print'] = 0;
                    $this->FeedBack[] = "Missing Print for Test case " . ($i +1) . " Expected: " . $SolRunPrintResults[$i] ;
                }
            } else if (isset($AnsRunPrintResults[$i])){
                if ($AnsRunPrintResults[$i] != ""){
                    $TestCaseResult['Print'] = 0;
                    $this->FeedBack[] = "Unexpected Print for Test case " . ($i +1) . " Printed: " . $AnsRunPrintResults[$i] ;
                }
            }
            
            if(isset($SolRunReturnResults[$i])){
                if(isset($AnsRunReturnResults[$i])){
                    if($SolRunReturnResults[$i] == $AnsRunReturnResults[$i]){
                         $this->FeedBack[] = "Return Match with Test case " . ($i +1) . " Output: " . $AnsRunReturnResults[$i];
                        
                        $TestCaseResult['Return'] = 100;
                    } else{
                        
                        
                         $simi = $this->Diff($SolRunReturnResults[$i],$AnsRunReturnResults[$i]);
                         
                         $this->FeedBack[] = "Return mismatch(" . $simi ."%) for Test case " . ($i +1) . " Output: " . $AnsRunReturnResults[$i] . " Expected: " . $SolRunReturnResults[$i] ;
                        
                         $TestCaseResult['Return'] = 50 * ($simi/100);

                    }
                    
                } else{
                    $TestCaseResult['Return'] = 0;
                    $this->FeedBack[] = "Missing Return for Test case " . ($i +1) . " Expected: " . $SolRunReturnResults[$i] ;
                }
            }else if (isset($AnsRunReturnResults[$i])){
                $TestCaseResult['Return'] = 0;
                $this->FeedBack[] = "Unexpected Return for Test case " . ($i +1) . " Returned: " . $AnsRunReturnResults[$i] ;
            }
            $this->Result['run'][] = $TestCaseResult;
        }
        

        

    }

    private function ExecGrading($code, $FunctionName, $FunctionReturns) {

        $OutputResult = [];

        $this->CleanUpFiles();

        $FileName = "TestCases";

        $FileContent = $this->MakeJavaTemplate($FileName, $code, $FunctionName, $FunctionReturns);

        $resCreate = $this->CreateJavaFile($FileName, $FileContent);

        $OutputResult['compile'] = $this->CompileJavaFile($FileName);

        $OutputResult['run'] = $this->RunJavaFile($FileName);

        return $OutputResult;
    }

    private function MakeJavaTemplate($ClassName, $TestFunction, $FunctionName, $isFunctionOutput = true) {

        $content = '
                public class ' . $ClassName . ' {

                public static void main(String[] args) {
                    ' . $ClassName . ' ts = new ' . $ClassName . '(); 
                    Object res;';
                    
        
        foreach ($this->TestCase as $TestCase){
        
        
                $content .= '    System.out.println("|printstart|"); ';

                    if ($isFunctionOutput) {
                        $content.= 'res = ts.' . $FunctionName . '(' . $TestCase . ');
                                    System.out.println("");
                                    System.out.println("|printend|");
                                    System.out.println("|returnstart|");
                                    System.out.println(res);
                                    
                                    System.out.println("");
                                    System.out.println("|returnend|");
                                    ';
                                    
                    } else {
                        $content.= 'ts.' . $FunctionName . '(' . $TestCase . ');';
                    
                        $content .= 'System.out.println("");
                                 System.out.println("|printend|");';
                    }
                    
        }
                $content .= ' } ';


        $content .= $TestFunction;
        $content .= ' }';
        return $content;
    }

    private function CreateJavaFile($fileName, $fileContent) {

        $myfile = fopen("UPLOADS/ExamGrading/" . $fileName . ".java", "w") or die("Unable to open file!");

        fwrite($myfile, $fileContent);
        fclose($myfile);
    }

    private function CompileJavaFile($fileName) {
        return $this->PhpExec("javac -nowarn UPLOADS/ExamGrading/" . $fileName . ".java 2>&1");
    }

    private function RunJavaFile($fileName) {
        return $this->PhpExec("java -cp UPLOADS/ExamGrading/ " . $fileName . " 2>&1");
    }

    function PhpExec($Cmd) {
        exec($Cmd, $ExecOutput, $out);
        return $ExecOutput;
    }

    private function CleanUpFiles() {
        $this->delTree("UPLOADS/ExamGrading");
        mkdir("UPLOADS/ExamGrading");
    }

    //Source: http://php.net/manual/en/function.rmdir.php
    function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

}

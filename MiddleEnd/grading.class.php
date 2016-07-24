<?php

// Requires an active session
// session_start();
class grading {

    public $solution = "";
    public $answer = "";
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
            , "void", "class", "finally", "long", "strictfp", "volatile", "const", "float", "native", "super", "while"];
    }

    function DoGrading() {

        //Simple String Match
        $MatchPerc = $this::Diff($this->solution, $this->answer);

        $this->Result['StringMatching'] = $MatchPerc / 100;
        $this->FeedBack[] = "Answer and Solution Similarity: " . $this->Result['StringMatching'] * 100 . "%";


        //Parse Tokens
        $this->SolutionTokens = $this->ParseTokens($this->solution);
        $this->AnswerTokens = $this->ParseTokens($this->answer);

        $EstMax = $this->GetEstimateTokenScore();

        $this->Result['Completness'] = $EstMax;
        $this->FeedBack[] = "Estimate of Token Matching: " . $EstMax * 100 . "%";



        $Leven = $this->GetLevenshteinEstimate();

        $this->Result['Levenshetein'] = ( $Leven);
        $this->FeedBack[] = "Estimated Levenshtein Score: " . $this->Result['Levenshetein'] * 100 . "%";



        $this->CalculateScore();
    }

    function CalculateScore() {

        //50% For String Matching
        $score = 40 * $this->Result['StringMatching'];

        //25% For Token Matching

        $score += 30 * $this->Result['Levenshetein'];

        $score += 30 * $this->Result['Completness'];


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

    public function FormatString($str) {
        /* Replace New Lines with Spaces */
        $str = str_replace("[\n]", " ", $str);
        $str = str_replace("<br />", " ", $str);
        $str = trim($str);

        foreach ($this->TOKENS as $Type => $Tokens) {
            if ($Type == "KEYWORDS") {
                continue;
            }
            foreach ($Tokens as $t) {
                $str = str_replace($t, " " . $t . " ", $str);
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

}

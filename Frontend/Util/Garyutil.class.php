<?php

// Requires an active session
// session_start();
class util {

    const UTIL_BASEURL = "https://web.njit.edu/~jl366/?p=";

    static function httpPost($url, $data, $usePOST = true) {
        if (isset($_SESSION["cookies"]) && isset($_SESSION["cookies"]["PHPSESSID"])) {
            $sessid = $_SESSION["cookies"]["PHPSESSID"];
        }

        if (!$usePOST) {
            $url .= "?";
            foreach ($data as $dKey => $dVal) {
                $url .= $dKey . "=" . $dVal . "&";
            }
        }

        $curl = curl_init($url);
        if($usePOST){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        // FIXME: It may be necessary to persist all cookies
        if (isset($sessid)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: PHPSESSID=" . $sessid));
        }
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            echo curl_error($curl);
        }
        curl_close($curl);
        list($header, $body) = explode("\r\n\r\n", $response, 2);
        preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $header, $ms);
        
        $cookies = array();
        if (count($ms[1]) > 0) {
            foreach ($ms[1] as $m) {
                list($name, $value) = explode('=', $m, 2);
                $cookies[$name] = $value;
            }
            $_SESSION["cookies"] = $cookies;
        }
        
        return $body;
    }

    static function ForwardPostRequest($PageRequest, $PostParams = array()) {
        $url = self::UTIL_BASEURL . $PageRequest;
        $res = self::httpPost($url, $PostParams);
       
        return json_decode($res, true);
    }

    static function ForwardGetRequest($PageRequest, $GetParams = array()) {
        $url = self::UTIL_BASEURL . $PageRequest;
        $res = self::httpPost($url, $GetParams, false);
         return json_decode($res, true);
    }

    static function ForwardDeleteRequest($PageRequest, $DeleteParams = array()) {
        
        $DeleteParams['method'] = "delete";
        $url = self::UTIL_BASEURL . $PageRequest;
        $res = self::httpPost($url, $DeleteParams, true);
         return json_decode($res, true);
    }
    
    static function ForwardPatchRequest($PageRequest, $PatchParams = array()) {
        
        $PatchParams['method'] = "patch";
        $url = self::UTIL_BASEURL . $PageRequest;
        $res = self::httpPost($url, $PatchParams, true);
         return json_decode($res, true);
    }

    static function Redirect($page) {
        header('Location: ' . $page);
    }

    static function IsInstructor() {
        return ($_SESSION['role'] && $_SESSION['role'] === "instructor");
    }

    static function VerifyRole($role) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != $role) {
            Util::Redirect('index.php');
        }
    }

    static function GetUser() {
        return json_decode(util::ForwardGetRequest("user.php")["result"]);
    }

    static function GetUserID() {
        return self::GetUser()->id;
    }
}
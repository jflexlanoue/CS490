<?php

// Requires an active session
// session_start();

class util {
    const UTIL_BASEURL = "https://web.njit.edu/~glh4/";

    static function httpPost($url, $data){
        if(isset($_SESSION["cookies"]) && isset($_SESSION["cookies"]["PHPSESSID"])) {
            echo "session found";
            $sessid = $_SESSION["cookies"]["PHPSESSID"];
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);

        // FIXME: It may be necessary to persist all cookies
        if(isset($sessid)) {
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
        foreach ($ms[1] as $m) {
            list($name, $value) = explode('=', $m, 2);
            $cookies[$name] = $value;
        }
        $_SESSION["cookies"] =  $cookies;
        return $body;
    }

    static function IsLoggedIn(){
        $url = self::UTIL_BASEURL . "session_info.php";

        $res = self::httpPost($url, array());
        $dec_res = json_decode($res);

        return $dec_res;
    }

    static function LogInUser($Username, $Password){
        $url = self::UTIL_BASEURL . "backend_login.php";

        $Data =  array("username" => $Username, "password" => $Password );
        $res = self::httpPost($url, $Data);
        $dec_res = json_decode($res);
        return $dec_res;
    }
}

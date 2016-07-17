<?php

class util { 
    

   const UTIL_BASEURL = "https://web.njit.edu/~glh4/";

    static function httpPost($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
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

<?php

class CookieService {
    public function setTokenCookie($token){
        $expires = time() + 3600;
        $path = '/';
        $domain = '';
        $secure = false;
        $httponly = false;
        $samesite = 'Lax';

        setcookie('token', $token, [
            'expires' => $expires, 
            'path' => $path, 
            'domain' => $domain, 
            'secure' => $secure, 
            'httponly' => $httponly,
            'samesite' => $samesite,
        ]);
    }

    // NOTE: next time front-end nalang yung implementation ng cookies 
    //       kasi issend din naman yung JWT sa front-end para ma-access 
    //       sa Authorization header at isend sa request

    public function getTokenCookie(){
        if(isset($_COOKIE['token'])){
            return $_COOKIE['token'];
        }
        return null;
    }

    public function clearTokenCookie() {
        setcookie('token', '', [
            'expires' => time() - 3600, // Expire the cookie
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    }

    public function flushAllCookies(){
        foreach ($_COOKIE as $key => $value){
            setcookie($key, '', [
                'expires' => time() - 3600, // Expire the cookie
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httponly' => false,
                'samesite' => 'Lax',
            ]);
        }
    }
}
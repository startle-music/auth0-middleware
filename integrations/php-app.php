<?php
/**
 *  Integration with third party PHP scripts
 * 
 *  This can be included in other php scripts in order to 
 *  check for and parse a token within a cookie
 * 
 * 
 * 
 * 
 */

/**
 *  Autoload all classes installed by composer
 * 
 */
require_once('../vendor/autoload.php');

use \Firebase\JWT\JWT;

class Auth {

    private $cookie = 'stauth';
    private $key = 'dSErfa324fSDEs';
    private $hash = ['HS256'];
    private $token = NULL;

    public function cookieExists() 
    {
        return isset($_COOKIE[$this->cookie]);
    }

    public function getTokenEmail()
    {
        $token = $this->parseCookie();

        return $token['uid'];
    }

    public function cookieIsValid()
    {
        $token = $this->parseCookie();

        if ($token === NULL) {
            return false;
        }
        return true;
    }

    public function parseCookie()
    {
        if (!$this->token) {
            try {
                $token = JWT::decode( $this->key, $this->hash );
                
                if (isset($token['uid'])) {
                    $this->token = $token;
                } else {
                    $this->token = NULL;
                }
            } catch (Exception $e) {
                $this->token = NULL;
            }
        }
        return $this->token;
    }
}

$_email = null;
$auth = new Auth();
// check for token
if ($auth->cookieExists()) {

    if($auth->cookieIsValid()) {
        // decode token
        $_email = $this->getUserEmail();
    }
}

define( 'AUTHED_EMAIL', $_email);

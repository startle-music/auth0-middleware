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

    public function cookieExists() 
    {
        return isset($_COOKIE[$this->cookie]);
    }

    public function getTokenEmail()
    {
        $token = $this->parseCookie();

        return $token['uid'];
    }

    public function parseCookie()
    {
        return JWT::decode( $this->key, $this->hash );
    }
}

$auth = new Auth();
// check for token
if ($auth->cookieExists()) {
    // decode token
    define( 'AUTHED_EMAIL', $this->getUserEmail());

} else {
    print('ERROR - NO COOKIE');
}

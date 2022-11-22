<?php

namespace App\controllers;

use Auth0\SDK\Auth0;
use \Firebase\JWT\JWT;

class HealthCheck
{
    /**
     * get method handler
     *
     * @return redirect
     */
    public function post()
    {
        return $this->get();
    }

    /**
     * get method handler
     *
     * @return redirect
     */
    public function get()
    {
        print_r('OK');
    }
}

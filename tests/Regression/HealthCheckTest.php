<?php

namespace Tests\Regression;

use App\controllers\HealthCheck;
use PHPUnit\Framework\TestCase;

class HealthCheckTest extends TestCase
{
    public function testGetPrintsOk()
    {
        $controller = new HealthCheck();

        $this->expectOutputString('OK');
        $controller->get();
    }

    public function testPostDelegatesToGet()
    {
        $controller = new HealthCheck();

        $this->expectOutputString('OK');
        $controller->post();
    }
}

<?php

use Silex\WebTestCase;

class ComputationTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . '/../app.php';
    }

    public function testArrayCount()
    {
        $array = [1,2,3];

        $this->assertEquals(3, count($array));
    }
}
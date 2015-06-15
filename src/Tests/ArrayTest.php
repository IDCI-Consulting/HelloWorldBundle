<?php

namespace Test;

use Silex\WebTestCase;

class ArrayTest extends WebTestCase
{
    public function createApplication()
    {
        return include __DIR__.'/../app.php';
    }

    public function testArrayCount()
    {
        $array = [1,2,3];

        $this->assertEquals(3, count($array));
    }
}

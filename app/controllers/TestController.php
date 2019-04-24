<?php
namespace App\Controllers;

use App\Models\Test;

class TestController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('a', '123123');
    }

    public function testAction()
    {
        $this->view->disable();
        $test = Test::find();
        var_dump($test->toArray());
    }

}


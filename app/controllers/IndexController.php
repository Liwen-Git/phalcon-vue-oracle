<?php
namespace App\Controllers;

use App\Library\Common;
use App\Models\Test;
use App\Models\User;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('a', '123123');
    }

    public function testAction()
    {
        $this->view->disable();
        $test = new Test();
        var_dump($test->toArray());
    }

}


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
//        $session = $this->session->set('liwen', 'cool');
//        var_dump($this->session->get('liwen'));
        $test = Test::findFirst(['account' => 'admin']);
        var_dump($test->toArray());
    }

}


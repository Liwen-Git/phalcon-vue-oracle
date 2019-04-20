<?php


namespace App\Controllers;


class AdminController extends ControllerBase
{
    public function indexAction()
    {
        echo 'ha';
        $this->view->disable();
    }
}

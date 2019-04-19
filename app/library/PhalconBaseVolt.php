<?php


namespace App\Library;


use Phalcon\Mvc\View\Engine\Volt;

class PhalconBaseVolt extends Volt
{
    public function initFunction()
    {
        $compiler = $this->getCompiler();

        $compiler->addFunction('mix', function ($path) {
            return '\App\Library\Common::mix(' .$path . ')';
        });
    }
}

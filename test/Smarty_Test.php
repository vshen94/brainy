<?php

namespace Box\Brainy\Tests;


class Smarty_Test extends Smarty_TestCase
{
    public function test_fetchedTemplate() {
        $smarty = new ExtendedSmarty();
        $this->setUpInstance($smarty);
        $smarty->fetch('helloworld.tpl');
        $this->assertEquals('helloworld.tpl', $smarty->fetched);
    }
    public function test_fetchedTemplate_include() {
        $smarty = new ExtendedSmarty();
        $this->setUpInstance($smarty);
        $smarty->fetch('eval:{include file="helloworld2.tpl"}');
        $this->assertEquals('helloworld2.tpl', $smarty->fetched);
    }
}


class ExtendedSmarty extends \Box\Brainy\Brainy
{
    public $fetched = null;

    public function fetchedTemplate($name)
    {
        $this->fetched = $name;
    }
}

<?php

namespace BurningDiode\Slim\Config;

class ParametersTest extends \PHPUnit_Framework_TestCase
{
    public function testGlobalParameters()
    {
        $data = array('Item 1', 'Item 2', 'Item 3');

        $yamlLoader = new YamlLoader()
            ->addParameters(array('item1' => 'Item 1'))
            ->addParameters(array('item2' => 'Item 2'))
            ->addFile(dirname(__FILE__) . '/fixtures/global.yml');

        $this->assertEquals($data, $yamlLoader->getConfig('items'));
    }
}

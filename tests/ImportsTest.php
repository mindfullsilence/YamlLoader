<?php

namespace BurningDiode\Slim\Config;

class ImportsTest extends \PHPUnit_Framework_TestCase
{
    public function testImports()
    {
        $data = array('Item 1', 'Item 2', 'Item 3');

        $yamlLoader = new YamlLoader();
          ->addFile(dirname(__FILE__) . '/fixtures/imports.yml');

        $this->assertEquals($data, $yamlLoader->getConfig('items'));
    }

    public function testMergeImports()
    {
        $data = array('item-a' => 'Item A', 'item-1' => 'Item 1', 'item-2' => 'Item 2', 'item-3' => 'Item 3');

        $yamlLoader = new YamlLoader();
          ->addFile(dirname(__FILE__) . '/fixtures/merge1.yml');

        $this->assertEquals($data, $yamlLoader->getConfig('items'));
    }
}

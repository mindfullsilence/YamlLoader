<?php

namespace BurningDiode\Slim\Config;

class AddFileTest extends \PHPUnit_Framework_TestCase
{
    public function testAddFile()
    {

        $data = array('item1', 'item2');

        $yamlLoader = new YamlLoader();

        $yamlLoader->addFile(dirname(__FILE__) . '/fixtures/index.yml');

        $this->assertEquals($data, $yamlLoader->getConfig());
    }
}

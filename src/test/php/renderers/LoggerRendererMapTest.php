<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @category   tests   
 * @package    log4php
 * @subpackage renderers
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    $Revision$
 * @link       http://logging.apache.org/log4php
 */

class CostumObjectRenderer implements LoggerRenderer {
	public function render($o) {
		return true;
	}
}

class Fruit3 {
    public $test1 = 'test1';
    public $test2 = 'test2';
    public $test3 = 'test3';
}

class Fruit3Descendant extends Fruit3 {
}

class FruitRenderer3 implements LoggerRenderer {
    public function render($o) {
		return $o->test1.','.$o->test2.','.$o->test3;
	}
}

class SampleObject {
	
}

/**
 * @group renderers
 */
class LoggerRendererMapTest extends PHPUnit_Framework_TestCase {
        
	public function testFindAndRender() {
		$fruit = new Fruit3();
		Logger::configure(dirname(__FILE__).'/test4.properties');
		$hierarchy = Logger::getHierarchy();
		
		$map = $hierarchy->getRendererMap();
		$e = $map->findAndRender($fruit);
		self::assertEquals('test1,test2,test3', $e);
	}
        
	public function testFindAndRenderDescendants() {
		$fruit = new Fruit3Descendant();
		Logger::configure(dirname(__FILE__).'/test4.properties');
		$hierarchy = Logger::getHierarchy();

		$map = $hierarchy->getRendererMap();
		$e = $map->findAndRender($fruit);
		self::assertEquals('test1,test2,test3', $e);
	}

	public function testGetByObject() {
		$fruit = new Fruit3();
		Logger::configure(dirname(__FILE__).'/test4.properties');
		$hierarchy = Logger::getHierarchy();
		
		$map = $hierarchy->getRendererMap();
		$e = $map->getByObject($fruit);
		self::assertTrue($e instanceof FruitRenderer3);
	}
        
	public function testGetByClassName() {
		Logger::configure(dirname(__FILE__).'/test4.properties');
		$hierarchy = Logger::getHierarchy();
		
		$map = $hierarchy->getRendererMap();
		$e = $map->getByClassName('Fruit3');
		self::assertTrue($e instanceof FruitRenderer3);
	}
	
	public function testUsage() {
	    Logger::resetConfiguration();
        Logger::configure(dirname(__FILE__).'/test4.properties');
        $logger = Logger::getRootLogger();
 
        ob_start();
        $logger->warn(new Fruit3());
        $actual = ob_get_contents();
        ob_end_clean();

        $expected = "WARN - test1,test2,test3" . PHP_EOL;
        self::assertEquals($expected, $actual);
	}
	
	public function testGetByObject_CostumRendererShouldRenderObject() {
		$sampleObject = new SampleObject();
		
		Logger::configure(dirname(__FILE__).'/test4.properties');
		$hierarchy = Logger::getHierarchy();
		
		$map = $hierarchy->getRendererMap();
		$map->setDefaultObjectRenderer(new CostumObjectRenderer());
		
		$actual = $map->findAndRender($sampleObject);
		
		$this->assertTrue($actual, 'costumobjectrenderer was rendered object');
	}
}

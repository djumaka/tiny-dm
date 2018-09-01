<?php

namespace DependencyManager\Tests;

require_once 'src/DependencyManager.php';
require_once 'tests/DummyTestClasses.php';

/**
 * Created by PhpStorm.
 * User: boyan
 * Date: 8/30/2018
 * Time: 7:24 AM
 */

use \PHPUnit\Framework\Testcase;
use DependencyManager\DependencyManager;

class DependencyManagerTest extends TestCase
{
    private $di;

    protected function setUp()
    {
        $dependencies = [
            BasicDummy::class   => [],
            ComplexDummy::class => [
                'dependencies' => [BasicDummy::class]
            ]
        ];

        $this->di = new DependencyManager($dependencies);
    }

    protected function tearDown()
    {
        unset($this->di);
    }

    public function testConstructFailParams()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DependencyManager([]);
    }

    public function testCreateBasicClass()
    {
        $basicDummy = $this->di->createInstance(BasicDummy::class);
        $this->assertEquals(BasicDummy::class, get_class($basicDummy));
    }

    public function testCreateComplexClass()
    {
        $complexDummy = $this->di->createInstance(ComplexDummy::class);
        $this->assertEquals(ComplexDummy::class, get_class($complexDummy));
    }
}

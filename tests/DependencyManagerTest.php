<?php

namespace Djumaka\TinyDm\Tests;

require_once 'src/DependencyManager.php';
require_once 'tests/DummyTestClasses.php';

use Djumaka\TinyDm\DependencyManager;
use PHPUnit\Framework\TestCase;

class DependencyManagerTest extends TestCase
{
    private $di;

    protected function setUp(): void
    {
        $dependencies = [
            BasicDummy::class   => [],
            ComplexDummy::class => [
                'dependencies' => [BasicDummy::class]
            ]
        ];

        $this->di = new DependencyManager($dependencies);
    }

    protected function tearDown(): void
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

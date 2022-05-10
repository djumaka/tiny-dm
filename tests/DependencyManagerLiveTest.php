<?php

namespace Djumaka\TinyDm\Tests;

require_once 'src/DependencyManager.php';
require_once 'tests/DummyTestClasses.php';

use Djumaka\TinyDm\DependencyManager;
use PHPUnit\Framework\TestCase;

class DependencyManagerLiveTest extends TestCase
{
    private DependencyManager $di;

    public function setUp(): void
    {
        $this->di = new DependencyManager([], true);
    }

    public function testLiveDM(): void
    {
        $complexClass = $this->di->createInstance(ComplexDummy::class);
        $this->assertEquals(ComplexDummy::class, get_class($complexClass));

    }

    public function testCircularReferenceException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->di->createInstance(CircularSecondClass::class);
    }
}

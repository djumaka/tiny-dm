<?php

namespace DependencyManager\Tests;

require_once 'src/DependencyManager.php';
require_once 'tests/DummyTestClasses.php';

/**
 * Created by PhpStorm.
 * User: boyan
 * Date: 8/30/2018
 * Time: 9:11 AM
 */

use DependencyManager\DependencyManager;
use PHPUnit\Framework\TestCase;

class DependencyManagerLiveTest extends TestCase
{
    private $di;

    public function setUp()
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

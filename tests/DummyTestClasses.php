<?php

namespace DependencyManager\Tests;
/**
 * Created by PhpStorm.
 * User: boyan
 * Date: 8/30/2018
 * Time: 8:59 AM
 */

class BasicDummy
{

}

class ComplexDummy {
    public function __construct(BasicDummy $basicClass)
    {
    }
}

class CircularBasicClass {
    public function __construct(CircularSecondClass $secondClass)
    {
    }
}

class CircularSecondClass {
    public function __construct(CircularBasicClass $basic)
    {
    }
}


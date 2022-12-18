<?php

namespace Djumaka\TinyDm\Tests;

class BasicDummy
{

}

class ComplexDummy {
    public function __construct(BasicDummy $basicClass)
    {
    }
}

class ComplexUndefinedDummy {
    public function __construct($basicClass)
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


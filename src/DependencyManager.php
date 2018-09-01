<?php

namespace DependencyManager;

class DependencyManager
{
    private $classes;
    private $live;
    private $dependencyTree = [];

    /**
     * DependencyManager constructor.
     *
     * @param array $classList
     * @param bool $live
     *
     * @throws \Exception
     */
    public function __construct(Array $classList, $live = false)
    {
        if ( ! $live && empty($classList)) {
            throw new \InvalidArgumentException('Missing class dependency map.');
        }

        $this->live    = $live;
        $this->classes = $classList;
    }


    /**
     * Create instance of class, recursing into its dependencies
     *
     * @param $className
     *
     * @return object
     * @throws \Exception
     */
    public function createInstance($className)
    {
        if ( ! $this->live && ! isset($this->classes[$className])) {
            throw new \InvalidArgumentException('Class not found ' . $className);
        }

        $r = $this->autoWireArgumentsAndGetClassReflection($className);


        if (in_array($className, $this->dependencyTree, true)) {
            throw new \InvalidArgumentException('Circular reference detected:'
                                                . "\n\t{$className}\n\t"
                                                . implode("\n\t", $this->dependencyTree));
        }

        $this->dependencyTree[] = $className;
        $constructorParams      = $this->resolveDependencies($className);
        array_pop($this->dependencyTree);

        return null === $r->getConstructor() ? $r->newInstance() : $r->newInstanceArgs($constructorParams);
    }


    /**
     * Define constructor dependencies if autowire is used.
     *
     * @param $className
     *
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    protected function autoWireArgumentsAndGetClassReflection($className): \ReflectionClass
    {
        $classReflection = new \ReflectionClass($className);
        if ($this->live) {
            $this->classes[$className]['dependencies'] = [];

            if (null !== $classReflection->getConstructor()) {
                $reflection = new \ReflectionMethod($className, '__construct');
                foreach ($reflection->getParameters() as $param) {
                    if (null === $param->getType()) {
                        throw new \InvalidArgumentException('Unspecified constructor argument '
                                                            . $param->getName() . ' for class' . $className);
                    }
                    $this->classes[$className]['dependencies'][] = $param->getType()->getName();
                }
            }
        }

        return $classReflection;
    }

    /**
     * Resolve all constructor dependencies.
     *
     * @param $className
     *
     * @return array
     * @throws \Exception
     */
    private function resolveDependencies($className): array
    {
        $constructorParams = [];

        $classDependencies = $this->classes[$className] ?: [];
        if ( ! empty($classDependencies['dependencies'])) {
            foreach ($classDependencies['dependencies'] as $classDependency) {
                $constructorParams[] = $this->createInstance($classDependency);
            }
        }

        return $constructorParams;
    }
}
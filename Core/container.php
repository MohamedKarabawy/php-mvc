<?php

namespace Core;

use ReflectionClass;
use Exception;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, $concrete = null)
    {
        // Use the abstract class as the concrete implementation when no implementation is provided
        if ($concrete === null) {
            $concrete = $abstract;
        }
        // Store the relationship between the abstract type and its concrete implementation
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, $concrete = null)
    {
        // Use the abstract class as the concrete implementation when no implementation is provided
        if ($concrete === null) {
            $concrete = $abstract;
        }
        // Store a closure that will build the concrete implementation when it is resolved
        $this->bindings[$abstract] = function ($container) use ($concrete) {
            return $container->build($concrete);
        };
    }

    public function get(string $abstract)
    {
        // Return the existing instance when the dependency has already been resolved as a singleton
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Get the registered implementation or use the requested class itself
        $concrete = $this->bindings[$abstract] ?? $abstract;

        // Check if the binding uses a closure and resolve it through the container
        if ($concrete instanceof \Closure) {
            $object = $concrete($this);

            // Store the resolved object when the binding represents a singleton
            if (isset($this->bindings[$abstract]) && $this->isSingleton($abstract)) {
                $this->instances[$abstract] = $object;
            }
            return $object;
        }

        // Build the requested concrete class and resolve its dependencies
        return $this->build($concrete);
    }

    public function build($concrete)
    {
        // Execute the closure directly when the concrete implementation is a closure
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        try {
            // Create a reflection instance to inspect the class and its constructor
            $reflector = new ReflectionClass($concrete);
        } catch (Exception $e) {
            // Throw a clear exception when the requested class cannot be found
            throw new Exception("Target class [$concrete] does not exist.");
        }

        // Check whether the class can be instantiated before attempting to create it
        if (!$reflector->isInstantiable()) {
            throw new Exception("Target [$concrete] is not instantiable.");
        }
        // Get the class constructor to determine its required dependencies
        $constructor = $reflector->getConstructor();

        // Instantiate the class directly when it does not have a constructor
        if ($constructor === null) {
            return new $concrete;
        }
        // Extract the constructor parameters to determine the required dependencies
        $dependencies = $constructor->getParameters();

        // Resolve each constructor dependency through the container
        $instances = $this->resolveDependencies($dependencies);

        // Create the class instance using the resolved dependencies
        return $reflector->newInstanceArgs($instances);
    }

    private function resolveDependencies(array $dependencies): array
    {
        $results = [];

        // Resolve each constructor dependency individually
        foreach ($dependencies as $dependency) {
            // Get the type information of the current dependency
            $type = $dependency->getType();

            // Handle dependencies that do not have a class type or use a built-in PHP type
            if (!$type || $type->isBuiltin()) {
                // Use the parameter's default value when one is available
                if ($dependency->isDefaultValueAvailable()) {
                    $results[] = $dependency->getDefaultValue();
                    continue;
                }

                // Throw an exception when the dependency cannot be resolved
                throw new Exception("Unresolvable dependency [{$dependency->getName()}] in class {$dependency->getDeclaringClass()->getName()}");
            }

            // Resolve the class dependency recursively through the container
            $results[] = $this->get($type->getName());
        }

        return $results;
    }

    private function isSingleton(string $abstract): bool
    {
        // Check whether the requested type has a registered closure binding
        return isset($this->bindings[$abstract]) && ($this->bindings[$abstract] instanceof \Closure);
    }
}

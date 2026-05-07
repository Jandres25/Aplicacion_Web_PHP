<?php

namespace Core;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

class Container
{
    /** @var array<string, string|Closure> */
    private array $bindings = [];

    /** @var array<string, true> */
    private array $singletonKeys = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function bind(string $abstract, string|Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, string|Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
        $this->singletonKeys[$abstract] = true;
    }

    public function instance(string $abstract, mixed $value): void
    {
        $this->instances[$abstract] = $value;
    }

    public function resolve(string $abstract): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract] ?? $abstract;
        $isSingleton = isset($this->singletonKeys[$abstract]);

        $object = $concrete instanceof Closure
            ? $concrete($this)
            : $this->build($concrete);

        if ($isSingleton) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    private function build(string $class): object
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new RuntimeException("[$class] no es instanciable.");
        }

        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class();
        }

        $params = $constructor->getParameters();
        if ($params === []) {
            return new $class();
        }

        $dependencies = [];
        foreach ($params as $param) {
            $type = $param->getType();
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                    continue;
                }
                throw new RuntimeException(
                    "No se puede resolver el parámetro [{$param->getName()}] en [$class]."
                );
            }
            $dependencies[] = $this->resolve($type->getName());
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}

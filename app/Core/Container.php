<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\ContainerException;
use App\Core\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

final class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $instances = [];

    public function set(string $id, mixed $concrete): void
    {
        $this->bindings[$id] = $concrete;
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (array_key_exists($id, $this->bindings)) {
            $concrete = $this->bindings[$id];
            $object = is_callable($concrete) ? $concrete($this) : $concrete;
            $this->instances[$id] = $object;
            return $object;
        }

        if (!class_exists($id)) {
            throw new NotFoundException("No entry found for {$id}");
        }

        try {
            $reflection = new ReflectionClass($id);
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                $object = new $id();
            } else {
                $dependencies = [];
                foreach ($constructor->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    if ($type === null) {
                        throw new ContainerException("Unresolvable dependency: {$parameter->getName()}");
                    }
                    $dependencies[] = $this->get($type->getName());
                }
                $object = $reflection->newInstanceArgs($dependencies);
            }

            $this->instances[$id] = $object;
            return $object;
        } catch (ReflectionException $exception) {
            throw new ContainerException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }

    public function has(string $id): bool
    {
        return isset($this->instances[$id]) || array_key_exists($id, $this->bindings) || class_exists($id);
    }
}

<?php

declare(strict_types=1);

namespace DependencyResolver\src\Container;

use ContainerMapException;
use DependencyResolver\src\Contracts\InstanceMap;
use Throwable;

class Container
{
    /**
     * @var array
     */
    private array $classes = [];

    /**
     * @param InstanceMap $map
     *
     * @throws ContainerMapException
     */
    final public function __construct(InstanceMap $map)
    {
        $m = $this->getRegisterInstances($map);
        foreach ($map->getInstanceMap() as $instance) {
            try {
                $constrParams = [];

                foreach ($instance::getConstructDependencies() as $depName) {
                    $constrParams[] = new $m[$depName];
                }

                $this->classes[$instance] = new $instance(...$constrParams);
            } catch (Throwable $e) {
                throw new ContainerMapException($e->getMessage());
            }
        }
    }

    /**
     * @param InstanceMap $map
     *
     * @return array
     */
    private function getRegisterInstances(InstanceMap $map): array
    {
        $m = [];
        foreach ($map->getRegisterMap() as $item) {
            $m[$item] = new $item();
        }
        return $m;
    }

    /**
     * @param string $className
     *
     * @return object|null
     */
    public function getInstance(string $className): ?object
    {
        return $this->classes[$className] ?? null;
    }
}

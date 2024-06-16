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
    private array $instances = [];

    /**
     * @param InstanceMap $map
     *
     * @throws ContainerMapException
     */
    final public function __construct(InstanceMap $map)
    {
        $this->setRegisterInstances($map);
        $this->setInstances($map);
    }

    /**
     * @param InstanceMap $map
     *
     * @return void
     */
    private function setRegisterInstances(InstanceMap $map): void
    {
        foreach ($map->getRegisterMap() as $item) {
            $this->instances[$item] = new $item();
        }
    }

    /**
     * @param InstanceMap $map
     * @return void
     *
     * @throws ContainerMapException
     */
    private function setInstances(InstanceMap $map): void
    {
        foreach ($map->getInstanceMap() as $instance) {
            try {
                $constrParams = [];
                foreach ($instance::getConstructDependencies() as $depName) {
                    $constrParams[] = new $this->instances[$depName];
                }
                $this->instances[$instance] = new $instance(...$constrParams);
            } catch (Throwable $e) {
                throw new ContainerMapException($e->getMessage());
            }
        }
    }

    /**
     * @param string $className
     *
     * @return object|null
     */
    public function getInstance(string $className): ?object
    {
        return $this->instances[$className] ?? null;
    }
}

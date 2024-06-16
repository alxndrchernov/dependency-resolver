<?php

namespace DependencyResolver\src\Contracts;

interface InstanceMap
{
    /**
     * All dependencies
     *
     * @return array
     */
    public function getRegisterMap(): array;

    /**
     * Dependencies for container
     *
     * @return array
     */
    public function getInstanceMap(): array;
}

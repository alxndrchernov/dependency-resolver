<?php

namespace DependencyResolver\src\Contracts;

interface ContainerImplementation
{
    /**
     * Get all params for constructor
     *
     * @return array
     */
    public static function getConstructDependencies(): array;
}

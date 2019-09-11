<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;

interface ActiveableInterface
{
    public function isActive(): bool;

    public function setActive(bool $active): void;
}

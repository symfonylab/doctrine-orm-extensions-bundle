<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;


interface SortableInterface
{
    public function getPosition(): ?int;

    public function setPosition(int $position): void;
}
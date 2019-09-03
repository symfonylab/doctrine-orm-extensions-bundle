<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;

interface SoftDeleteableInterface
{
    public function getDeletedAt():?\DateTimeImmutable;

    public function setDeletedAt(\DateTimeImmutable $deleteAt): void;
}
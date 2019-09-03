<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeleteableTrait
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $deletedAt;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deleteAt): void
    {
        $this->deletedAt = $deleteAt;
    }
}
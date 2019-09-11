<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ActiveableTrait
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}

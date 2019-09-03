<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SortableTrait
{
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $position = 0;

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }
}
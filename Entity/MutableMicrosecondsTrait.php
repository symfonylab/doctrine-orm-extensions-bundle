<?php


namespace SymfonyLab\DoctrineOrmExtensionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait MutableMicrosecondsTrait
{
    /**
     * @ORM\Column(type="datetime_immutable_microseconds")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable_microseconds")
     */
    protected $updatedAt;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

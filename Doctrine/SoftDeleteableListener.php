<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine;

use Doctrine\ORM\Event\PreFlushEventArgs;
use SymfonyLab\DoctrineOrmExtensionsBundle\Entity\SoftDeleteableInterface;

class SoftDeleteableListener
{
    public function preFlush(PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        foreach ($em->getUnitOfWork()->getScheduledEntityDeletions() as $object) {
            if (!$object instanceof SoftDeleteableInterface) {
                continue;
            }
            if ($object->getDeletedAt()) {
                continue;
            }

            $object->setDeletedAt(new \DateTimeImmutable());
            $em->merge($object);
            $em->persist($object);
        }
    }
}
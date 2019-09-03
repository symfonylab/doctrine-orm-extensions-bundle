<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use SymfonyLab\DoctrineOrmExtensionsBundle\Entity\MutableInterface;

class MutableListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->supports($entity)) {
            return;
        }

        $entity->setCreatedAt(new \DateTimeImmutable());
        $entity->setUpdatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->supports($entity)) {
            return;
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());

        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function supports($entity): bool
    {
        return $entity instanceof MutableInterface;
    }
}
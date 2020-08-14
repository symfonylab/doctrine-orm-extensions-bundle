<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
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

        $entity->setCreatedAt($entity->getCreatedAt() ?: new \DateTimeImmutable());
        $entity->setUpdatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->supports($entity)) {
            return;
        }
        /*
         * @var MutableInterface $entity
         */
        $entity->setUpdatedAt(new \DateTimeImmutable());

        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(\get_class($entity));
        $this->checkAndSetEmbeddedFields($em, $meta, $entity);
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function checkAndSetEmbeddedFields(EntityManagerInterface $em, ClassMetadata $meta, MutableInterface $entity)
    {
        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($entity);
        if (empty($changeSet)) return;
        $changed = array_map(function ($f) {
            [$field,] = explode('.', $f);

            return $field;
        }, array_keys($changeSet));

        foreach ($meta->embeddedClasses as $field => $class) {
            $eentity = $meta->getFieldValue($entity, $field);
            if (!$eentity || !$this->supports($eentity) || !\in_array($field, $changed)) {
                continue;
            }
            $eentity->setCreatedAt($eentity->getCreatedAt() ?: new \DateTimeImmutable());
            $eentity->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    private function supports($entity): bool
    {
        return $entity instanceof MutableInterface;
    }
}

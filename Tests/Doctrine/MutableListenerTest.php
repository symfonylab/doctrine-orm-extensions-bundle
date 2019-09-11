<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Tests\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;
use SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine\MutableListener;
use SymfonyLab\DoctrineOrmExtensionsBundle\Entity\MutableInterface;

class MutableListenerTest extends TestCase
{
    public function testUpdate()
    {
        $entity = $this->getMockBuilder(MutableInterface::class)->getMock();
        $entity->expects($this->exactly(1))->method('setUpdatedAt');
        $entity->expects($this->exactly(0))->method('setCreatedAt');

        $listener = new MutableListener();
        $listener->preUpdate($this->getEventMock($entity));
    }

    public function testCreate()
    {
        $entity = $this->getMockBuilder(MutableInterface::class)->getMock();
        $entity->expects($this->exactly(1))->method('setUpdatedAt');
        $entity->expects($this->exactly(1))->method('setCreatedAt');

        $listener = new MutableListener();
        $listener->prePersist($this->getEventMock($entity));
    }

    protected function getEventMock(MutableInterface $entity)
    {
        $entityManager = $this->getMockForAbstractClass(EntityManagerInterface::class);
        $entityManager->method('getClassMetadata')->willReturn($this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock());
        $entityManager->method('getUnitOfWork')->willReturn($this->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock());

        $event = $this->getMockBuilder(LifecycleEventArgs::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event->method('getEntity')->willReturn($entity);
        $event->method('getEntityManager')->willReturn($entityManager);

        return $event;
    }
}

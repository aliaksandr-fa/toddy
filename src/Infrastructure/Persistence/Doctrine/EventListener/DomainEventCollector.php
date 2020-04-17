<?php

namespace Toddy\Infrastructure\Persistence\Doctrine\EventListener;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Proxy\Proxy;
use Doctrine\ORM\UnitOfWork;
use SimpleBus\SymfonyBridge\Bus\EventBus;


/**
 * Class DomainEventCollector
 * @package Nexus\Application\Service\DomainEvent\EventListener
 * @author Faley Aliaksandr
 */
class DomainEventCollector implements EventSubscriber
{
    /**
     * @var mixed[]
     */
    private $collectedEvents = array();

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * DomainEventCollector constructor.
     * @param EventBus $eventBus
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return array(
            Events::onFlush,
            Events::postFlush,
        );
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em  = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        $this->checkChangeSet($uow);
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function postFlush(PostFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getEntityManager();

        $hasProceededEntities = false;

        foreach ($this->collectedEvents as $event) {
            $hasProceededEntities = true;

            $this->eventBus->handle($event);
        }

        $this->collectedEvents = [];

        if ($hasProceededEntities) {
            $em->flush();
        }
    }

    /**
     * @param UnitOfWork $uow
     */
    private function checkChangeSet(UnitOfWork $uow): void
    {
        $entitiesWithChanges = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );

        foreach ($entitiesWithChanges as $entity) {
            $this->collectEventsFromEntity($entity);
        }
    }

    /**
     * @param mixed $entity
     */
    private function collectEventsFromEntity($entity): void
    {
        if ($entity instanceof ContainsRecordedMessages
            && (!$entity instanceof Proxy)
        ) {
            $messages = $entity->recordedMessages();
            if (!$messages) {
                return;
            }
            foreach ($messages as $message) {
                $this->collectedEvents[] = $message;
            }
            $entity->eraseMessages();
        }
    }
}

<?php
/**
 * Logs all entity operations in the database
 *
 * @package    LosLos\Log
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLog\Log;
use \Doctrine\Common\EventSubscriber;

/**
 * Logs all entity operations in the database
 *
 * @package    LosLos\Log
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
class EntityLogger extends AbstractLogger implements EventSubscriber
{
    /*
     * (non-PHPdoc) @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents ()
    {
        return array(
            'onFlush'
        );
    }

    /**
     * Logs the entity changes
     *
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush (\Doctrine\ORM\Event\OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->debug('Inserting entity ' . get_class($entity) . '. Fields: ' .
                             json_encode($uow->getEntityChangeSet($entity)));
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $add = '';
            if (method_exists($entity, '__toString')) {
                $add = ' '. $entity->__toString();
            } elseif (method_exists($entity, 'getId')) {
                $add = ' with id '. $entity->getId();
            }

            $this->debug('Updating entity ' . get_class($entity) . $add .'. Data: ' .
                             json_encode($uow->getEntityChangeSet($entity)));
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $add = '';
            if (method_exists($entity, '__toString')) {
                $add = ' '. $entity->__toString();
            } elseif (method_exists($entity, 'getId')) {
                $add = ' with id '. $entity->getId();
            }

            $this->debug('Deleting entity ' . get_class($entity) . $add . '.');
        }

        //TODO
        //foreach ($uow->getScheduledCollectionDeletions() as $col) {}
        //foreach ($uow->getScheduledCollectionUpdates() as $col) {}
    }
}

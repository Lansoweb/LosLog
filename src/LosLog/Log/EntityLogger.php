<?php
/**
 * Logs all entity operations in the database
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Log;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Common\Util\Debug;

/**
 * Logs all entity operations in the database
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
class EntityLogger extends AbstractLogger implements EventSubscriber
{
    /*
     * (non-PHPdoc) @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return [
            'onFlush',
        ];
    }

    /**
     * Logs the entity changes
     *
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->debug('Inserting entity '.get_class($entity).'. Fields: '.
                             json_encode($uow->getEntityChangeSet($entity)));
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $add = '';
            if (method_exists($entity, '__toString')) {
                $add = ' '.$entity->__toString();
            } elseif (method_exists($entity, 'getId')) {
                $add = ' with id '.$entity->getId();
            }

            $this->debug('Updating entity '.get_class($entity).$add.'. Data: '.
                             json_encode($uow->getEntityChangeSet($entity)));
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $add = '';
            if (method_exists($entity, '__toString')) {
                $add = ' '.$entity->__toString();
            } elseif (method_exists($entity, 'getId')) {
                $add = ' with id '.$entity->getId();
            }

            $this->debug('Deleting entity '.get_class($entity).$add.'.');
        }
    }

    public static function dump($entity, $maxDepth = 1, $toHtml = true)
    {
        $output = print_r(Debug::export($entity, $maxDepth), true);

        if ($toHtml) {
            echo "<pre>$output</pre>";
        } else {
            echo $output;
        }
    }
}

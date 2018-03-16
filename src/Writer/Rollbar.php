<?php

namespace LosMiddleware\LosLog\Writer;

use DateTime;
use Rollbar\Payload\Level;
use RollbarNotifier;
use Zend\Log\Formatter\FormatterInterface;
use Zend\Log\Writer\AbstractWriter;

/**
 * Rollbar log writer.
 */
class Rollbar extends AbstractWriter
{
    /**
     * This writer does not support formatting.
     *
     * @param string|FormatterInterface $formatter
     *
     * @param array|null $options
     * @return Rollbar
     */
    public function setFormatter($formatter, ?array $options = null)
    {
        return $this;
    }

    /**
     * Write a message to the log.
     *
     * @param array $event Event data
     */
    protected function doWrite(array $event)
    {

        if (isset($event['timestamp']) && $event['timestamp'] instanceof DateTime) {
            $event['timestamp'] = $event['timestamp']->format(DateTime::W3C);
        }
        $extra = array_diff_key($event, ['message' => '', 'priorityName' => '', 'priority' => 0]);

        \Rollbar\Rollbar::log($event['priorityName'], $event['message'], $extra);
    }
}

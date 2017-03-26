<?php

namespace LosMiddleware\LosLog\Writer;

use DateTime;
use RollbarNotifier;
use Zend\Log\Writer\AbstractWriter;

/**
 * Rollbar log writer.
 */
class Rollbar extends AbstractWriter
{
    /**
     * \RollbarNotifier.
     */
    protected $rollbar;

    /**
     * Constructor.
     *
     * @params \RollbarNotifier $rollbar
     */
    public function __construct(RollbarNotifier $rollbar)
    {
        $this->rollbar = $rollbar;
    }

    /**
     * This writer does not support formatting.
     *
     * @param string|FormatterInterface $formatter
     *
     * @return WriterInterface
     */
    public function setFormatter($formatter)
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

        $this->rollbar->report_message($event['message'], $event['priorityName'], $extra);
    }
}

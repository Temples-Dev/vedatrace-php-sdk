<?php

namespace VedaTrace\Laravel;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use VedaTrace\Logger;

class VedaTraceHandler extends AbstractProcessingHandler
{
    protected Logger $logger;

    public function __construct(Logger $logger, $level = \Monolog\Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->logger = $logger;
    }

    protected function write(LogRecord $record): void
    {
        $this->logger->log(
            $record->level->toPsrLogLevel(),
            $record->message,
            $record->context
        );
    }
}

<?php

namespace VedaTrace\Transports;

interface TransportInterface
{
    /**
     * @param array $logs An array of log entry arrays.
     * @return void
     */
    public function send(array $logs): void;
}

<?php

namespace Search2d\Infrastructure\Domain;

interface QueueSender
{
    /**
     * @param string $body
     * @return void
     */
    public function send(string $body): void;
}
<?php

namespace Onyx\AMQP\Example\Workers;

use Puzzle\AMQP\Workers\Worker;
use Puzzle\AMQP\ReadableMessage;
use Psr\Log\LoggerAwareTrait;

class ExampleWorker implements Worker
{
    use LoggerAwareTrait;

    public function process(ReadableMessage $message)
    {
        $this->logger->info(sprintf('message uncompressed : %s[...]', substr($message->getBodyInOriginalFormat(), 0, 50)));
    }
}

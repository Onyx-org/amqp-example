<?php

namespace Onyx\AMQP\Example\Workers;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Puzzle\AMQP\Workers\WorkerContext;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['worker.example'] = function($c) {
            $context = new WorkerContext(function() use($c) {
                    return new ExampleWorker();
                },
                $c['amqp.consumers.simple'],
                'compressed'
            );

            $context
                ->setDescription('Worker that consumme message')
                ->setLogger($c['logger.workers']);

            return $context;
        };
    }
}

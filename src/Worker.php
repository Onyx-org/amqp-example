<?php

namespace Onyx\AMQP\Example;

use Pimple\Container;
use Puzzle\AMQP\Workers\WorkerCommands;

class Worker
{
    private
        $app,
        $configuration;

    public function __construct(Container $container)
    {
        $this->configuration = $container['configuration'];

        $this->app = new \Onyx\Console\Application();

        $this->registerAmqpWorkers($container);
    }

    public function run(): void
    {
        $this->app->run();
    }

    private function registerAmqpWorkers(Application $container): void
    {
        $workers = new WorkerCommands(
            $this->app,
            $container['amqp.client'],
            $container['amqp.workerProvider'],
            $container['logger.global.handlers']
        );

        $workers->register();
    }
}
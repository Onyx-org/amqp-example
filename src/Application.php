<?php

namespace Onyx\AMQP\Example;

use Silex\Provider\SessionServiceProvider;
use Onyx\Providers;
use Puzzle\AMQP\Silex\AmqpServiceProvider;
use Puzzle\AMQP\Client;
use Puzzle\AMQP\Workers\WorkerProvider;
use Puzzle\AMQP\Messages\Processors\GZip;
use Puzzle\AMQP\Messages\Processors\AddHeader;

class Application extends \Onyx\Application
{
    protected function registerProviders(): void
    {
        $this->register(new SessionServiceProvider());
        $this->register(new Providers\Monolog([
            "amqp"
        ]));
        $this->register(new Providers\Twig());
        $this->register(new Providers\Webpack());
        $this->register(new AmqpServiceProvider());
    }

    protected function initializeServices(): void
    {
        $this->configureTwig();
        $this->configureAmqp();
    }

    private function configureTwig(): void
    {
        $this['view.manager']->addPath(array(
            $this['root.path'] . 'views/',
        ));
    }
    
    private function configureAmqp(): void
    {
        $this[WorkerProvider::MESSAGE_PROCESSORS_SERVICE_KEY] = function ($c){
            return [
                new AddHeader(["X-Context" => "Onyx AMQP example"]),
                new GZip()
            ];
        };
        
        $this->extend('amqp.client', function(Client $client, $c) {
          
            $client->setMessageProcessors($c[WorkerProvider::MESSAGE_PROCESSORS_SERVICE_KEY]);
            $client->setLogger($c['logger.amqp']);
            
            return $client;
        });
    }

    protected function mountControllerProviders(): void
    {
        $this->mount('/', new Controllers\Home\Provider());
    }
}

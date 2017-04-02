<?php

namespace Onyx\AMQP\Example\Controllers\Home;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Puzzle\AMQP\Client;
use Puzzle\AMQP\Messages\Message;

class Controller
{
    use
        Traits\RequestAware,
        Traits\TwigAware,
        LoggerAwareTrait;

    private
        $amqp;
        
    public function __construct(Client $amqp)
    {
        $this->logger = new NullLogger();
        $this->amqp = $amqp;
    }

    public function homeAction(): Response
    {
        $message = new Message("zip.example");
        $message->setText(str_repeat("0123456789", 100000));
        $message->allowCompression();
        $message->disallowSilentDropping();
        
        $this->amqp->publish('example', $message);
        
        return $this->render('home.twig');
    }
}

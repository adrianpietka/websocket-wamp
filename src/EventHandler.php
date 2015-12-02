<?php

namespace MyApp;

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class EventHandler implements WampServerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->logger->info('Registred handler');
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->logger->info('New connection: ' . $conn->resourceId);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->logger->info('Close connection: ' . $conn->resourceId);
    }

    public function onSubscribe(ConnectionInterface $conn, $subscription)
    {
        $this->logger->info('Connection ' . $conn->resourceId . ' has new subscription: ' . $subscription->getId());
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->logger->info('Connection ' . $conn->resourceId . ' unsubscribe: ' . $topic);
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $this->logger->info($conn->resourceId . ' publish data into subscription: ' . $topic->getId());
        $topic->broadcast($event);
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $this->logger->warning($conn->resourceId . ' called RPC');
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->logger->critical($conn->resourceId . ' exception: ' . $e->getMessage());
    }
}
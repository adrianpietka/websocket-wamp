<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__ . '/vendor/autoload.php';

$logger = new Logger('websocket');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
$logger->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));
$logger->pushHandler(new StreamHandler('php://stderr', Logger::CRITICAL));

$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);
$socket->listen(9000, '0.0.0.0');

$server = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer(
                new MyApp\EventHandler($logger)
            )
        )
    ),
    $socket
);

$loop->run();
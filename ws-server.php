<?php

require_once(__DIR__ . "/phpunit_bootstrap.php");

echo sprintf("File %s started at %s", basename(__FILE__), date("Y-m-d H:i:s")) , PHP_EOL;

$webSocketDaemon = \YusamHub\WebSocket\WebSocketFactory::newDaemon(
    \YusamHub\WebSocket\WebSocketFactory::newConfig(include('config/web-socket-server.php')),
    \YusamHub\WebSocket\WebSocketFactory::newOutput()
);

$webSocketDaemon->setDebugging(true);

$webSocketDaemon->run(
    [
        \YusamHub\WebSocket\WsServer\IncomingMessages\PingPongIncomingMessage::class,
    ],
    [
        \YusamHub\WebSocket\WsServer\ExternalMessages\PingPongExternalMessage::class,
    ]
);


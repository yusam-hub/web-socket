<?php

require_once(__DIR__ . "/phpunit_bootstrap.php");

echo sprintf("File %s started at %s", basename(__FILE__), date("Y-m-d H:i:s")), PHP_EOL;

$webSocketClient = new \YusamHub\WebSocket\WebSocketClient(
    \YusamHub\WebSocket\WebSocketFactory::newConfig(
        include('config/web-socket-client.php')
    ),
    \YusamHub\WebSocket\WebSocketFactory::newOutput()
);

try {

    $webSocketClient->daemon([
        \YusamHub\WebSocket\WsClient\OutgoingMessages\PingOutgoingMessage::class,
    ],[
        \YusamHub\WebSocket\WsClient\IncomingMessages\PongIncomingMessage::class,
    ]);

} catch (\Throwable $e) {
    echo $e->getMessage(), PHP_EOL;
}





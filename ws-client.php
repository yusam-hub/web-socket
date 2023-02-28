<?php

require_once(__DIR__ . "/phpunit_bootstrap.php");

\YusamHub\Debug\Debug::instance()->nddPrint(sprintf("File %s started at %s", basename(__FILE__), date("Y-m-d H:i:s")));

$webSocketClient = new \YusamHub\WebSocket\WebSocketClient(
    \YusamHub\WebSocket\WebSocketFactory::newConfig(
        include('config/web-socket-server.php')
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
    \YusamHub\Debug\Debug::instance()->nddPrint($e->getMessage());
}





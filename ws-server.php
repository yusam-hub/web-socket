<?php

require_once(__DIR__ . "/phpunit_bootstrap.php");

\YusamHub\Debug\Debug::instance()->nddPrint(sprintf("File %s started at %s", basename(__FILE__), date("Y-m-d H:i:s")));

$webSocketDaemon = \YusamHub\WebSocket\WebSocketFactory::newDaemon(
    \YusamHub\WebSocket\WebSocketFactory::newConfig(include('config/web-socket-server.php')),
    \YusamHub\WebSocket\WebSocketFactory::newOutput()
);

$webSocketDaemon->setDebugging(true);

$webSocketDaemon->run();


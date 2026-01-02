<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketConfigInterface;
use YusamHub\WebSocket\Interfaces\WebSocketDaemonInterface;
use YusamHub\WebSocket\Interfaces\WebSocketOutputInterface;

/**
 * Class WebSocketDaemon
 * @package YusamHub\WebSocket
 */
class WebSocketDaemon implements WebSocketDaemonInterface
{
    /**
     * @var bool
     */
    protected bool $isDebugging = false;

    /**
     * @var WebSocketConfigInterface
     */
    protected WebSocketConfigInterface $webSocketConfig;

    /**
     * @var WebSocketOutputInterface
     */
    protected WebSocketOutputInterface $webSocketOutput;

    /**
     * WebSocketDaemon constructor.
     * @param WebSocketConfigInterface $webSocketConfig
     * @param WebSocketOutputInterface $webSocketOutput
     */
    public function __construct(
        WebSocketConfigInterface $webSocketConfig,
        WebSocketOutputInterface $webSocketOutput
    )
    {
        $this->webSocketConfig = $webSocketConfig;
        $this->webSocketOutput = $webSocketOutput;
    }

    /**
     * @return bool
     */
    public function isDebugging(): bool
    {
        return $this->isDebugging;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setDebugging(bool $value): void
    {
        $this->isDebugging = true;
    }

    /**
     * @param array $incomingMessagesClass
     * @param array $externalMessagesClass
     * @return void
     * @throws \ZMQSocketException
     */
    public function run(array $incomingMessagesClass = [], array $externalMessagesClass = []): void
    {
        $this->webSocketOutput->echoInfo(
            sprintf(
                'Web Socket Server: %s:%s, External Messages Server: %s:%s',
                $this->webSocketConfig->getBindAddress(),
                $this->webSocketConfig->getBindPort(),
                $this->webSocketConfig->getBindPullAddress(),
                $this->webSocketConfig->getBindPullPort()
            )
        );

        $loop = \React\EventLoop\Loop::get();

        // === TCP-сервер для внешних сообщений вместо ZMQ ===
        $externalServer = new \React\Socket\TcpServer(
            sprintf('%s:%s', $this->webSocketConfig->getBindPullAddress(), $this->webSocketConfig->getBindPullPort()),
            $loop
        );

        // === WebSocket сервер ===
        $webSocketServer = WebSocketFactory::newServer($this, $incomingMessagesClass, $externalMessagesClass);

        $externalServer->on('connection', function (\React\Socket\ConnectionInterface $connection) use ($webSocketServer) {
            $connection->on('data', function ($data) use ($webSocketServer, $connection) {
                $messages = explode("\n", trim($data));
                foreach ($messages as $message) {
                    if ($message !== '') {
                        $webSocketServer->onExternalMessage($message);
                    }
                }
                $connection->write("ok\n"); // подтверждение
            });
        });

        $webUri = sprintf('%s:%s', $this->webSocketConfig->getBindAddress(), $this->webSocketConfig->getBindPort());
        $webSocket = new \React\Socket\SocketServer($webUri, [], $loop);

        // === Обработка сигналов и периодическая проверка на завершение ===
        $loop->addSignal(SIGTERM, function (int $signal) use ($loop, $webSocketServer) {
            $this->webSocketOutput->echoInfo("Signal received - SIGTERM");
            $webSocketServer->setShouldQuit(true);
        });

        $loop->addPeriodicTimer(5, function () use ($loop, $webSocketServer) {
            if ($webSocketServer->isShouldQuit() && $webSocketServer->countConnections() === 0) {
                $this->webSocketOutput->echoInfo("Server stopped");
                $loop->stop();
            }
        });

        new \Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer($webSocketServer)
            ),
            $webSocket
        );

        $loop->run();
    }

    /**
     * @return WebSocketConfigInterface
     */
    public function getWebSocketConfig(): WebSocketConfigInterface
    {
        return $this->webSocketConfig;
    }

    /**
     * @return WebSocketOutputInterface
     */
    public function getWebSocketOutput(): WebSocketOutputInterface
    {
        return $this->webSocketOutput;
    }


}
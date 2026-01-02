<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketConfigInterface;
use YusamHub\WebSocket\Interfaces\WebSocketOutputInterface;
use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientIncomingMessageInterface;
use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientInterface;
use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientOutgoingMessageInterface;
use ZMQ;
use ZMQContext;

/**
 * Class WebSocketClient
 * @package App\Common\WebSocket
 */
class WebSocketClient implements WebSocketClientInterface
{
    protected WebSocketConfigInterface $webSocketConfig;
    protected WebSocketOutputInterface $webSocketOutput;

    /**
     * WebSocketClient constructor.
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




    /**
     * @param array $outgoingMessageClasses
     * @param array $incomingMessageClasses
     * @param string $path
     * @param string $query
     * @return void
     * @throws \Exception
     */
    public function daemon(array $outgoingMessageClasses = [], array $incomingMessageClasses = [], string $path = '', string $query = ''): void
    {
        \Ratchet\Client\connect(
            sprintf('ws://%s:%s%s%s',
                $this->webSocketConfig->getBindAddress(),
                $this->webSocketConfig->getBindPort(),
                $path,
                (!empty($query)) ? '?'.$query : ''
            )
        )->then(
            function(\Ratchet\Client\WebSocket $conn) use ($outgoingMessageClasses, $incomingMessageClasses)
            {
                $conn->on('message', function(\Ratchet\RFC6455\Messaging\MessageInterface $msg) use ($conn, $incomingMessageClasses) {
                    $payload = $msg->getPayload();
                    $arrayMsg = @json_decode($payload, true);
                    $this->webSocketOutput->echoInfo("RECV", (is_array($arrayMsg) && !empty($arrayMsg)) ? $arrayMsg : [$payload]);

                    foreach($incomingMessageClasses as $objClass) {
                        $obj = new $objClass();
                        if ($obj instanceof WebSocketClientIncomingMessageInterface) {
                            $obj->setWebSocketClient($this);
                            $obj->setWebSocketClientConnection($conn);
                            $obj->setWebSocketMessage($payload, (array) $arrayMsg);
                            if ($obj->validate()) {
                                $obj->execute();
                                return;
                            }
                        }
                    }
                });

                $conn->on('close', function() use ($conn) {
                    $this->webSocketOutput->echoInfo("CLOSED");
                });

                foreach($outgoingMessageClasses as $objClass) {
                    $obj = new $objClass();
                    if ($obj instanceof WebSocketClientOutgoingMessageInterface) {
                        $obj->setWebSocketClient($this);
                        $obj->execute($conn);
                    }
                }
            },
            function ($e)
            {
                $this->webSocketOutput->echoException($e);
            }
        );
    }

    /**
     * @param string $stringMessage
     * @throws \Exception
     */
    public function externalSendStringMessage(string $stringMessage): void
    {
        $address = $this->webSocketConfig->getBindPullAddress();
        $port = $this->webSocketConfig->getBindPullPort();

        $this->webSocketOutput->echoInfo($stringMessage);

        $fp = @fsockopen($address, $port, $errno, $errstr, 1); // таймаут 1 секунда
        if (!$fp) {
            $this->webSocketOutput->echoInfo("Failed to connect to external server: $errstr ($errno)");
            return;
        }

        fwrite($fp, $stringMessage . "\n"); // отправляем сообщение с переносом строки
        fflush($fp);

        // необязательно, но можно прочитать ответ "ok"
        $response = fgets($fp);
        fclose($fp);
    }

    /**
     * @param array $arrayMessage
     * @param int $options
     * @throws \Exception
     */
    public function externalSendJsonMessage(array $arrayMessage, int $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): void
    {
        $this->externalSendStringMessage(json_encode($arrayMessage, $options));
    }
}

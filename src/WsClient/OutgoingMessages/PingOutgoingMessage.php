<?php

namespace YusamHub\WebSocket\WsClient\OutgoingMessages;

class PingOutgoingMessage extends BaseMessageOutgoingMessage
{
    /**
     * @param \Ratchet\Client\WebSocket $conn
     * @return void
     */
    public function execute(\Ratchet\Client\WebSocket $conn): void
    {
        $message = "PING";
        $this->wsClient->getWebSocketOutput()->echoInfo("SEND: " . $message);
        $conn->send($message);
    }
}
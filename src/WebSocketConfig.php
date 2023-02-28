<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketConfigInterface;

/**
 * Class WebSocketConfig
 * @package YusamHub\WebSocket
 */
class WebSocketConfig implements WebSocketConfigInterface
{
    /**
     * @var string
     */
    protected string $bindAddress;

    /**
     * @var string
     */
    protected string $bindPort;

    /**
     * @var string
     */
    protected string $bindPullAddress;

    /**
     * @var string
     */
    protected string $bindPullPort;

    /**
     * WebSocketConfig constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getBindAddress(): string
    {
        return $this->bindAddress;
    }

    /**
     * @return string
     */
    public function getBindPort(): string
    {
        return $this->bindPort;
    }

    /**
     * @return string
     */
    public function getBindPullAddress(): string
    {
        return $this->bindPullAddress;
    }

    /**
     * @return string
     */
    public function getBindPullPort(): string
    {
        return $this->bindPullPort;
    }

    /**
     * @return bool
     */
    public function isShellNetstatListen(): bool
    {
        return web_socket_is_netstat_listen($this->getBindAddress(), $this->getBindPort());
    }

    /**
     * @return bool
     */
    public function isShellNetstatListenPull(): bool
    {
        return web_socket_is_netstat_listen($this->getBindPullAddress(), $this->getBindPullPort());
    }

}
<?php

if (! function_exists('web_socket_is_netstat_listen')) {

    function web_socket_is_netstat_listen(string $address, string $port): bool
    {
        $command = sprintf("netstat -anp | grep LISTEN | grep %s:%s", $address, $port);
        $result = shell_exec($command);
        return strstr($result,sprintf("%s:%s", $address, $port));
    }
}
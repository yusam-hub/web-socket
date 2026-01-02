#### yusam-hub/web-socket

    "php": "^7.4 || ^8.3"

#### tests

    sh phpinit
    php ws-server.php
    php ws-client.php
    php ws-external.php

#### setup

    "repositories": {
        ...
        "yusam-hub/web-socket": {
            "type": "git",
            "url": "https://github.com/yusam-hub/web-socket.git"
        }
        ...
    },
    "require": {
        ...
        "yusam-hub/web-socket": "dev-master"
        "yusam-hub/web-socket": "^1.0"
        ...
    }
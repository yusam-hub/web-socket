#### dockers

    docker exec -it yusam-php74 bash
    docker exec -it yusam-php74 sh -c "htop"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && composer update"

#### testing

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-server.php"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-client.php"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-external.php"

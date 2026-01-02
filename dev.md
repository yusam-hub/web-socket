#### dockers

    docker exec -it dev-php74 bash
    docker exec -it dev-php74 sh -c "htop"

    docker exec -it dev-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && composer update"
    docker exec -it dev-php83 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && composer update"

#### testing php74

    docker exec -it dev-php74 sh -c "cd /var/www && exec bash"

    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/web-socket && composer install"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/web-socket && sh phpunit"

    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/web-socket && php ws-server.php"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/web-socket && php ws-client.php"
    docker exec -it dev-php74 sh -c "cd /var/www/php74/yusam-hub/web-socket && php ws-external.php"

#### testing php83

    docker exec -it dev-php83 sh -c "cd /var/www && exec bash"

    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/web-socket && composer install"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/web-socket && sh phpunit"

    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/web-socket && php ws-server.php"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/web-socket && php ws-client.php"
    docker exec -it dev-php83 sh -c "cd /var/www/php83/yusam-hub/web-socket && php ws-external.php"
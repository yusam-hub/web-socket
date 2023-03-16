#### dockers

    docker exec -it yusam-php74 bash
    docker exec -it yusam-php74 sh -c "htop"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && composer update"

#### testing php74

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-server.php"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-client.php"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-external.php"

#### testing php81

    docker exec -it yusam-php81 sh
    ping 10.0.0.74
    apk add nmap
    nmap -sS -p- -PS80,22 -n -T4 -vvv --reason 10.0.0.74

    docker exec -it yusam-php81 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-server.php"
    docker exec -it yusam-php81 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-client.php"
    docker exec -it yusam-php81 sh -c "cd /var/www/data/yusam/github/yusam-hub/web-socket && php ws-external.php"
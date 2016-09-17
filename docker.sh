#!/bin/sh

# project docker path
SCRIPT_DIR=`dirname $0`

case $1 in
    --help|help)
        echo "Usage: ./docker.sh [up|down] \n\n \
ps      - List containers \n \
up      - Starts the services in the background \n \
down    - Stops the services, removes the containers \n \
db      - Open mysql \n \
ssh     - Connect SSH \n \
build   - Docker compose build \n \
log     - Check service logs \n"
        ;;
    ps)
            # Starts the docker machine and backgroud services
            (docker-compose ps)
            ;;
    up)
        # Starts the docker machine and backgroud services
        (docker-compose up -d)
        ;;
    down)
        # Stops the services, removes the containers
        (docker-compose down)
        ;;
    db)
        # Open mysql
        (mysql -u zendphp -pzendphp -Dzendphp -h 127.0.0.1)
        ;;
    ssh)
        # Connect to SSH
        (docker exec -it zendphp_server_1 bash)
        ;;
    build)
        # Connect to SSH
        (docker-compose build)
        ;;
    log)
        # Check service logs
        (docker-compose logs)
        ;;
    *)
        echo "unknown command"
        echo "Usage:"
        echo "./docker.sh [ps|up|down|db|ssh|build]"
        ;;
esac

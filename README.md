# BASIC ZENDPHP
- Development environment for ZENDPHP with DOCKER COMPOSE
- [ ] Setup environment
- [ ] Init Zend framework
- [ ] Project 01: Album Application
- [ ] Project 02: Blog Application

## Setup environment
- Docker for windows https://docs.docker.com/docker-for-windows/
- Docker for mac https://docs.docker.com/docker-for-mac/

### Clone this repository
```
git clone https://github.com/lecaoquochung/zendphp.git
cd zendphp
docker-compose up

# /etc/hosts
127.0.0.1 zendphp.dev
```

Go to <http://zendphp.dev/> and you can see PHP works.
Go to <http://zendphp.dev/zf> and you can see ZF PHP works.

### Command (./docker.sh)
- `mysql -u zendphp -p zendphp -D zendphp -h 127.0.0.1`
- `docker-compose ps` shows the status of containers
- `docker exec -it zendphp_server_1 bash` enter the shell of a container
- `mysql -u zendphp -p zendphp -D zendphp -h 127.0.0.1` enter the MySQL console

## Init Zend framework
### Download composer
- Download Latest composer.phar https://getcomposer.org
```
wget https://getcomposer.org/composer.phar
```

### Zend framework

## Project 01: Album Application
### Init
```
./composer.phar create-project -s dev zendframework/skeleton-application album
```

### Module
```
"Album\\": "module/Album/src/"
./composer.phar dump-autoload
```

- Reference
 - https://docs.zendframework.com/tutorials/getting-started/modules/

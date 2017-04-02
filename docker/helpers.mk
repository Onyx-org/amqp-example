WEB_PORT=80

-include .settings.mk

export WEB_PORT
export RMQ_PORT
export COMPOSE_PROJECT_NAME=onyx_amqp

up: prepare-bash-history-directory docker-up wait rmq-configure

docker-up:
	docker-compose -f docker/docker-compose.yml up -d

build:
	docker-compose -f docker/docker-compose.yml build

rebuild: build up

update: down up

wait:
	sleep 5

down:
	docker-compose -f docker/docker-compose.yml down --volumes

connect:
	docker exec --tty -i onyx-frontend /bin/bash


prepare-bash-history-directory:
	$(shell [ ! -d system/bash-history ] && mkdir -p system/bash-history)
	touch system/bash-history/.frontend

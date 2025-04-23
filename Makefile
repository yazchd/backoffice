build: docker-compose.yml
	docker compose build

up: docker-compose.yml
	docker compose up -d

exec: docker-compose.yml
	docker exec -it www_docker_symfony bash

kill: docker-compose.yml
	docker compose kill

rm: docker-compose.yml
	docker compose  rm -f

stop: kill rm

start: build up

cli: start exec

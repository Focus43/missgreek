export COMPOSE_PROJECT_NAME = cugreekgods
SHELL = /bin/bash

dev: dev-rebuild
	docker-compose -f _docker/docker-compose.yml up -d
	docker-compose -f _docker/docker-compose.yml logs -f; true && \
	make dev-halt

dev-halt:
	docker-compose -f _docker/docker-compose.yml down

dev-rebuild:
	docker-compose -f _docker/docker-compose.yml build

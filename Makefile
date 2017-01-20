export COMPOSE_PROJECT_NAME = cugreekgods
SHELL = /bin/bash

.PHONY: dev
dev: dev-rebuild
	docker-compose -f _docker/docker-compose.yml up -d
	docker-compose -f _docker/docker-compose.yml logs -f; true && \
	make dev-halt

.PHONY: dev-halt
dev-halt:
	docker-compose -f _docker/docker-compose.yml down

.PHONY: dev-rebuild
dev-rebuild:
	docker-compose -f _docker/docker-compose.yml build

.PHONY: clean
clean:
	docker-compose -f _docker/docker-compose.yml down --rmi all

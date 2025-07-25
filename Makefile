docker_compose_exec = docker compose exec -T
docker_run = docker run
php_console = $(docker_compose_exec) fpm-apps-rnd bin/console
symfony_console = $(docker_compose_exec) bash symfony

###############
# Application #
###############

controller:
	$(php_console) make:controller

entity:
	$(php_console) make:entity

#########
# Utils #
#########

start:
	docker compose up -d --remove-orphans

stop:
	docker compose down

restart:
	docker compose down && docker compose up -d --remove-orphans --build

docker-clean:
	docker system prune --all --force && docker system prune --all --force --volumes

ps:
	 docker compose ps

## PHP bash
bash:
	docker compose exec fpm-apps-rnd bash

## Create admin
create-admin:
	$(php_console) app:create-admin "$ADMIN_EMAIL" "$ADMIN_PASSWORD" --env=prod

## Nginx reload
reload-nginx:
	docker exec nginx-station nginx -s reload

############
# Database #
############

## Create migration diff
db-drop:
	$(php_console) doctrine:database:drop --force --if-exists

db-reset: db-drop db-create db-schema

db-create:
	$(php_console) doctrine:database:create --if-not-exists

db-schema:
	$(php_console) doctrine:schema:create

db-dump-sql:
	$(php_console) doctrine:schema:update --dump-sql

db-schema-update-force:
	$(php_console) doctrine:schema:update --force

migrations-apply:
	$(php_console) doctrine:migrations:migrate -n

migrations-make:
	$(php_console) make:migration

fixtures:
	$(php_console) doctrine:fixtures:load -n

prod-fixtures: db-drop db-create db-schema
	@read -p " Charger les fixtures en PROD ? (y/N) " ans && \
	[ $${ans:-N} = y ] && \
	$(php_console) doctrine:fixtures:load -n --group=prod --env=prod

create-public-ao-index:
	$(php_console) app:create-public-ao-index

index-public-ao: create-public-ao-index
	$(php_console) app:index-public-ao

index-articles:
	$(php_console) app:index-articles

## Clear cache
cc: cache-clear

cache-clear:
	$(php_console) cache:clear

cache-delete:
	$(docker_compose_exec) fpm-apps-rnd rm -rf var/cache/*
	$(docker_compose_exec) fpm-apps-rnd chown -R www-data:www-data var/cache
	$(docker_compose_exec) fpm-apps-rnd chmod -R 775 var/cache
	$(docker_compose_exec) fpm-apps-rnd mkdir -p var/cache/prod/tcpdf
	$(php_console) cache:clear

logs-delete:
	$(docker_compose_exec) fpm-apps-rnd rm -rf app/var/log/*

## Corriger les permissions
fix-perms:
	$(docker_compose_exec) fpm-apps-rnd chmod -R 777 var

## Tout nettoyer (commande principale)
clean: fix-perms cache-clear
	@echo "✅ Cache complètement nettoyé et réchauffé"

## Permissions de fichiers
permissions-fix: logs-delete cache-clear cache-delete

## Aide (liste les commandes disponibles)
help:
	@echo "Commandes disponibles:"
	@echo "make clean      - Nettoie complètement le cache"
	@echo "make cache-clean - Supprime le cache sans réchauffement"
	@echo "make fix-perms  - Corrige les permissions"

save-elastic-data:
	docker run --rm \
	  -v esdata01:/volume \
	  -v $(shell pwd):/backup \
	  alpine tar czf /backup/esdata-backup.tar.gz -C /volume .

make pull:
	git pull && make cache-delete && make fix-perms
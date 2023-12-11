CONTAINER_NAME = app_container

start:
	docker-compose build
	composer install
	
stop:
	docker stop $(CONTAINER_NAME) 
	docker stop $(CONTAINER_NAME)-nginex
	docker stop $(CONTAINER_NAME)-postgres

up:
	docker-compose up --force-recreate

console: 
	docker exec -it $(CONTAINER_NAME) bash

down:
	docker-compose down

migration:
	docker exec -it $(CONTAINER_NAME) php bin/console doctrine:migrations:migrate

phpstan:
	composer --working-dir=tools/phpstan install
	tools/phpstan/vendor/bin/phpstan analyse src

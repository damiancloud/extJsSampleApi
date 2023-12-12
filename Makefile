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

.PHONY: change-app-id
change-app-id:
	sed -i 's/"id": ".*"/"id": "7e693b44-c287-481f-99b4-d78e420a0abe"/' vendor/sencha-sdks/app.json

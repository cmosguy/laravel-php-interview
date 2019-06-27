local-setup:
	@chmod +x docker/scripts/start.sh
	@cp .env.example .env
	@docker-compose -f docker-compose.yml -f dev.docker-compose.yml up -d --build
	@docker-compose exec --user=nginx app composer install
	@echo "\n Run the unit test suite"
	@docker-compose exec --user=nginx app vendor/bin/phpunit --testsuite V1Unit
	@echo "\n Run the feature test suite"
	@docker-compose exec --user=nginx app vendor/bin/phpunit --testsuite V1Feature
	@echo "\nCreate the database structure and seed dummy data"
	@docker-compose exec --user=nginx app php artisan migrate:fresh --seed
	@echo "\nApplication is up and running!"

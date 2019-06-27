# Laravel and PHP Interview Codebase
The purpose of this repository is meant to be a tool to for you the interviewer to be able to boot up the code and use.  We'll be discussing the fundamentals of:

1. Laravel's IoC Injection system
2. Object Oriented Programming principles
3. Docker
4. Database

## Requirements
1. Unix OS
2. Available `80` port or any value that is set in the `.env` file as `NGINX_PORT` value
3. Docker engine `1.13.0+` version or above
4. Docker compose `1.21+` version or above
5. `make` command installed
6. `git` installed
7. Check the `DOCKER_HOST_UID` to be equal to your local user `echo $UID` inside `.env`
8. Check the `DOCKER_HOST_GID` to be equal to your local user `echo $GID` inside `.env`

## Installation
1. `git clone https://github.com/cmosguy/laravel-php-interview.git`
1. Go to the `laravel-php-interview` folder and run `cp .env.example .env`. Here in your `.env` file set you desired values. 
Make sure that the `API_KEY` value is set properly since it is required for executing requests on `api/v1/tips` endpoint.
1. *THIS IS IMPORTANT*: Execute `make local-setup` to boot up the docker containers, that should be it
1. [Documentation link](https://documenter.getpostman.com/view/1567891/RzZAjxqw)
1. The `DatabaseSeeder` will seed `20` dummy records, from which 2 are with hardcoded `uuid` since they are used in the 
`Postman collection` that can be found under `docs/` folder.

## Useful stuff (maybe)
1. To boot up the docker containers use: `docker-compose -f docker-compose.yml -f dev.docker-compose.yml up -d --build`
1. `dev.docker-compose.yml` is used only to keep local cache of the composer files and to start the phpmyadmin service.
 It is not required for the application to boot successfully.
1. To run composer command `docker-compose exec --user=nginx composer`
1. To run artisan command `docker-compose exec --user=nginx php artisan`
1. To run unit tests `docker-compose exec --user=nginx vendor/bin/phpunit`
1. `--user=nginx` and `DOCKER_HOST_GID` with `DOCKER_HOST_UID` are used to preserve the user permissions inside the docker container
and outside of it and avoid file permissions issue where the files gets owned by the container user and locks up for the host user

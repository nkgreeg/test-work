# Posiflora test work 

## Notes

* All commands run from project root
* I use actual command `docker compose` but you can replace it to `docker-compose`

## Development

### Copy and edit `.env` config

`cp .env .env.local`

#### Telegram mock settings

* `.evn.local` set `TELEGRAM_MOCK` `true` to use mock telegram client that do not send message in chat

### Copy and edit `compose.override.yaml`

```shell
cp compose.override.example.yaml compose.override.yaml
```

### Up local database

```shell
docker compose -f compose.yaml -f compose.override.yaml up -d
```

### Prepare

```shell
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Create seed data

```shell
php bin/console doctrine:fixtures:load
```

### Run

```shell
symfony serve
```

### Tests

```shell
php bin/phpunit
```

## Docker

### Copy and edit `.env` config

```shell
cp .env.docker .env.docker.local
```

### Up

```shell
docker compose -f docker-compose.yaml --env-file .env.docker.local up --build
```

### TODO

* `docker-compose.yaml` do not use NGINX, just php -S
* No log. App or NGINX
* JavaScript in HTML-file without webpack

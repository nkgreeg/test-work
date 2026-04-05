# Posiflora test work 

## Notes

* All commands run from project root
* I use actual command `docker compose` but you can replace it to `docker-compose`

## Development

### Copy and edit `.env` config

`cp .env .env.local`

#### Telegram mock settings

* `.evn.local` set `TELEGRAM_MOCK` `true` to send log

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

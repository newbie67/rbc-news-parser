
Скопируйте docker-compose конфиг и поправьте если нужно:
```bash
copy docker-compose.yml.dist docker-compose.yml
```

Запустите контейнеры и подтяните зависимости
```bash
docker-compose up -d
docker-compose exec php composer install
```

Миграции
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
```

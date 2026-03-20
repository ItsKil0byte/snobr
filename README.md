# Snobr

**Snobr** (от слов "snob" и "habr") - хабр-like блог для тех, кто "не такой как все".

## Запуск

1. Клонируем репозиторий.
2. Настраиваем переменные окружения (.env).
3. Поднимаем контейнеры:

```bash
docker compose up -d
```

4. Ставим зависимости:

```bash
docker compose exec app composer install
docker compose exec app npm install
```

```bash
docker compose exec app php artisan key:generate
docker compose exec app npm run build
```

5. При необходимости сгенерируем тестовые данные:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

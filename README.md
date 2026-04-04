# SNOBR 🎩

**SNOBR** (от слов "snob" и "habr") - habr-like блог для тех, кто "не такой как все".

## Технологический стек
- **Backend:** PHP 8.3, Laravel 12
- **Frontend:** Blade, Tailwind CSS
- **База данных:** MySQL
- **Инфраструктура:** Docker (на базе образа [serversideup/php](https://serversideup.net/open-source/docker-php/))

## Команда

<details>
  <summary><b>Тык</b></summary>
  <br>
  <ul>
    <li>
        <b>Александр Лебедев</b> (@ItsKil0byte) - <i>Backend Developer</i>
        <br> 
        Проектирование базы данных, миграций и связей (Eloquent). 
        Разработка архитектуры сервисов: <code>FileService</code> для управления медиафайлами и 
        <code>SettingsService</code> для парсинга кастомной JSON-конфигурации. Настройка инфраструктуры.
    </li>
    <br>
    <li>
        <b>Егор Хотенов</b> (@quan4q) - <i>Security Developer</i>
        <br>
        Интеграция системы авторизации. Разработка системы ролей (RBAC). Написание политик доступа (Policies), 
        защитных Middleware и кастомной обработки HTTP-ошибок. Разработка Backend'a лайков и комментариев.
    </li>
    <br>
    <li>    
        <b>Александр Юнусов</b> (@lionalex02) - <i>Content Developer</i>
        <br>
        Разработка контроллеров для управления контентом. Вывод главной ленты, 
        добавление комментариев, загрузка файлов и страниц.
    </li>
    <br>
    <li>
        <b>Максим Разин</b> (@razinmax) - <i>Admin Developer</i>
        <br>
        Создание панели администратора для управления контентом. Реализация CRUD-интерфейсов для модерации 
        пользователей и категорий. Изменение глобальных настроек платформы "на лету".
    </li>
  </ul>
</details>

---

## Руководство по запуску

Для запуска проекта вам потребуются установленные **Git** и **Docker**.

**1. Клонируем репозиторий:**
```bash
git clone https://github.com/ItsKil0byte/snobr
cd snobr
```

**2. Настраиваем переменные окружения:**
```bash
cp .env.example .env
```

**3. Поднимаем контейнеры:**
```bash
docker compose up -d
```

**4. Устанавливаем PHP и Node.js зависимости:**
```bash
docker compose exec app composer install
docker compose exec app npm install
```

**5. Инициализируем приложение и его связи/стили:**
```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
docker compose exec app npm run build
```

**6. Подготавливаем базу данных:**
```bash
docker compose exec app php artisan migrate:fresh --seed
```

---

- _Сделано с любовью от студентов ИИТ ❤️_

# Desire House CMS — развёртывание и первый запуск

Документ описывает последовательность запуска нового экземпляра Desire House
CMS на чистом сервере. Проект использует PHP 8.3+, Laravel 13, PostgreSQL,
Filament 5, Node.js и Vite.

## 1. Системные требования

- PHP 8.3 или новее;
- PHP-расширения `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `fileinfo`,
  `intl`, `curl`, `zip`;
- PostgreSQL 17 или совместимая поддерживаемая версия;
- Composer 2;
- Node.js и npm для сборки frontend;
- web-сервер с document root, направленным в `laravel/public`.

Проверка основных компонентов:

```bash
php -v
php -m | grep -E 'pdo_pgsql|pgsql'
composer --version
psql --version
node --version
npm --version
```

## 2. Получение проекта и зависимостей

```bash
git clone <repository-url> desire-house
cd desire-house/laravel
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
```

Для development-окружения флаг `--no-dev` у Composer не используется.

## 3. Создание PostgreSQL-роли и базы

Этот этап выполняется администратором PostgreSQL. Laravel не должен работать
под ролью `postgres` и не должен иметь право создавать другие базы.

Пример команд внутри `psql`:

```sql
CREATE ROLE desire_house
    LOGIN
    PASSWORD '<strong-random-password>'
    NOSUPERUSER
    NOCREATEDB
    NOCREATEROLE;

CREATE DATABASE desire_house
    OWNER desire_house
    ENCODING 'UTF8'
    TEMPLATE template0;
```

Для автоматических тестов рекомендуется отдельная база:

```sql
CREATE DATABASE desire_house_test
    OWNER desire_house
    ENCODING 'UTF8'
    TEMPLATE template0;
```

## 4. Конфигурация приложения

```bash
cp .env.example .env
php artisan key:generate
```

Минимально необходимые значения `.env`:

```dotenv
APP_NAME="Desire House CMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=desire_house
DB_USERNAME=desire_house
DB_PASSWORD=<application-database-password>
DB_SSLMODE=prefer

INSTALL_ENABLED=false
INSTALL_TOKEN=
```

Production-файл `.env` не добавляется в Git. Доступ к нему должен быть только
у системного пользователя приложения.

## 5. Подготовка Laravel

```bash
php artisan migrate --force
php artisan storage:link
php artisan about --only=environment,drivers
php artisan migrate:status
```

Каталоги `storage` и `bootstrap/cache` должны быть доступны на запись
пользователю PHP-FPM или web-сервера.

## 6. Получение установочного токена

На сервере из каталога `laravel` выполнить:

```bash
php artisan setup:token
```

Команда:

1. генерирует криптографический токен из 32 случайных байт;
2. записывает `INSTALL_ENABLED=true` в локальный `.env`;
3. записывает новый `INSTALL_TOKEN`;
4. очищает config-cache;
5. показывает адрес мастера и токен в терминале.

Пример результата:

```text
Desire House CMS is ready for initial setup.
Setup URL: https://example.com/setup
Install token: <generated-token>
```

Токен необходимо скопировать из серверного терминала и вставить в форму
`/setup`. Он не отображается на web-странице: это защищает новый экземпляр от
захвата первым случайным посетителем.

Для Docker, Kubernetes и CI, где `.env` управляется извне:

```bash
php artisan setup:token --no-write
```

После этого значения передаются контейнеру вручную:

```dotenv
INSTALL_ENABLED=true
INSTALL_TOKEN=<generated-token>
```

Если `super_admin` уже существует, команда завершится с ошибкой. Флаг
`--force` допускается только для осознанного восстановления доступа:

```bash
php artisan setup:token --force
```

## 7. Перезапуск runtime

После изменения переменных окружения необходимо перезапустить долгоживущие
процессы:

```bash
php artisan queue:restart
```

Также перезапускаются PHP-FPM, Laravel Octane или запущенный `artisan serve`,
если они используются. Затем можно подготовить production-кеш:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 8. Создание первого администратора

1. Открыть `https://example.com/`.
2. Пока `super_admin` отсутствует, приложение перенаправит запрос на
   `https://example.com/setup`.
3. Указать имя, email и токен из команды `setup:token`.
4. Нажать «Создать администратора».
5. Сохранить показанный логин и одноразово отображённый пароль.
6. Перейти по кнопке в `/admin`.
7. Войти с временным паролем.
8. Система автоматически перенаправит на `/first-login/password` и не даст
   открыть CMS до установки нового пароля.
9. Ввести текущий временный пароль, новый пароль и его подтверждение.

Новый пароль должен содержать не менее 12 символов, строчные и заглавные
буквы, цифры и специальные символы. После успешной смены временный пароль
перестаёт действовать, флаг первого входа снимается, а пользователь
перенаправляется в `/admin`.

После назначения роли `super_admin` новые GET- и POST-запросы к `/setup`
перенаправляются на `/admin`. Обычный пользователь без этой роли не закрывает
мастер первоначальной настройки.

После успешной установки рекомендуется вернуть в серверном окружении:

```dotenv
INSTALL_ENABLED=false
INSTALL_TOKEN=
```

и снова выполнить:

```bash
php artisan config:cache
php artisan queue:restart
```

Даже если это не сделано сразу, проверка роли `super_admin` блокирует повторное
использование мастера.

## 9. Создание первой страницы

CMS намеренно не создаёт фиктивную главную страницу. После входа в Filament
администратор создаёт опубликованную страницу со slug `home`. До этого
публичный корневой URL после завершения setup возвращает контролируемый `404`.

## 10. Проверка установки

```bash
php artisan migrate:status
php artisan route:list --path=setup
php artisan test
```

Проверить вручную:

- `/admin` открывает форму входа;
- `/setup` перенаправляет на `/admin` после создания `super_admin`;
- `/up` возвращает успешный health response;
- пользователь без административной роли не получает доступ к Filament;
- администратор с временным паролем перенаправляется на
  `/first-login/password`;
- `.env`, `.env.testing`, `vendor` и `node_modules` не отслеживаются Git.

## Краткая последовательность команд

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
cp .env.example .env
# Настроить APP_URL и DB_* в .env
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan setup:token
# Перезапустить PHP runtime
# Открыть /setup и создать super_admin
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

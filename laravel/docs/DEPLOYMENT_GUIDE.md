# Desire House CMS — развёртывание, первый запуск и обновление

Документ описывает два сценария:

1. запуск нового экземпляра Desire House CMS на чистом сервере (разделы 1–10);
2. обновление уже работающего сайта с контентом в CMS (разделы 11–15).

Проект использует PHP 8.3+, Laravel 13, PostgreSQL, Filament 5, Node.js и Vite.

Срез проекта и смысл шагов: `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_4.md`.

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

## 11. Что в Git, а что в CMS

`git pull` меняет только код. Он не затирает главную, меню, настройки сайта,
услуги, проекты, заявки и загруженные файлы.

| В Git | В PostgreSQL / `storage` |
| --- | --- |
| приложение, миграции, шаблоны, CSS | `pages.blocks`, услуги, проекты |
| маршруты и провайдеры Builder | `site_settings`, `menu_items` |
| SEO/GEO-код | таблица `seo`, медиа |

Контент пропадает только от `migrate:fresh`, `db:seed`, `db:wipe`, ручного
`DELETE` или порчи каталога `storage`.

Текущий продакшен на дату контрольной точки 4.0:

```text
хост     199.189.255.204
SSH      deeploy
каталог  /var/www/desire-house/laravel
ветка    services
```

Document root web-сервера должен указывать на `laravel/public`.

## 12. Обновление уже работающего сервера

Этот сценарий — не первый запуск. База уже есть, страницы уже заполнены,
`.env` уже настроен. Не запускать `setup:token`, если `super_admin` уже
существует.

Работать пользователем приложения из каталога `laravel`:

```bash
ssh deeploy@199.189.255.204
cd /var/www/desire-house/laravel

git fetch origin
git checkout services
git pull origin services

composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
```

После миграций:

1. открыть публичный сайт и `/admin`;
2. если таблицы ролей только что появились — назначить `super_admin`
   существующему пользователю (раздел 14);
3. если `site_settings` пустая — сайт не должен падать; заполнить
   **Настройки → Сайт** в админке;
4. когда страницы открываются без 500, можно снова включить production-кеш:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

`php artisan optimize` допустим только после этой проверки. Если после него
снова 500 — сразу `php artisan optimize:clear`.

## 13. Порядок наполнения CMS после кода

Код не создаёт продающий сайт. Контент заполняется в Filament в таком порядке,
потому что блоки ссылаются друг на друга.

1. **Настройки → Сайт** — компания, телефон, email, Telegram, WhatsApp,
   адрес, логотип, favicon.
2. **Медиа → Изображения** — фото для Hero, услуг, проектов, логотипа.
3. **Контент → Услуги** — опубликованные записи со slug.
4. **Контент → Проекты** — работы, привязка к услугам, обложка, галерея.
5. **Настройки → Меню** — пункты шапки и подвала.
6. **Контент → Страницы** — опубликованные `home`, `uslugi` и свободные
   страницы («О компании», «Контакты»).
7. Секция **SEO** в карточках страниц, услуг и проектов.
8. Проверка заявки с публичной формы.

Страницы `/uslugi/{slug}` и `/raboty/{slug}` собирают шаблоны сущности.
Их не нужно вручную собирать блоками Builder.

Пока нет опубликованной страницы со slug `home`, корневой URL после setup
возвращает контролируемый 404. Это штатное состояние, а не ошибка деплоя.

## 14. Старый администратор после появления ролей

Миграция `create_permission_tables` создаёт таблицы, не роли и не назначения.

Пока у пользователя нет роли `super_admin` или `panel_user`, Filament не
пускает в панель (`User::canAccessPanel()`). Мастер `/setup` не примет
существующий email: поле уникально.

На сервере:

```bash
cd /var/www/desire-house/laravel
php artisan tinker
```

```php
use App\Models\User;
use Spatie\Permission\Models\Role;

User::query()->get(['id', 'name', 'email']);

$role = Role::query()->firstOrCreate([
    'name' => 'super_admin',
    'guard_name' => 'web',
]);

$user = User::query()->where('email', '<email-старого-админа>')->firstOrFail();
$user->assignRole($role);
$user->must_change_password = false;
$user->save();
```

Если пароль утерян, задать новый в той же сессии tinker и сразу сменить его
в **Настройки → Пользователи**. Временные пароли в Git не записывать.

Нового администратора на пустой базе создаёт сценарий разделов 6–8
(`setup:token` и `/setup`), а не этот раздел.

## 15. Запрещённые операции на живом сайте

Не выполнять:

```bash
php artisan migrate:fresh
php artisan migrate:fresh --seed
php artisan db:seed
php artisan db:wipe
```

Они уничтожают страницы, заявки, медиа-привязки и пользователей.

Если после `git pull` сайт отдаёт 500:

1. читать `storage/logs/laravel.log`, не запускать `migrate:fresh`;
2. `Undefined array key "settings"` — нужен коммит `575a8b0` или новее,
   затем `php artisan optimize:clear`;
3. отказ во входе в `/admin` при верном пароле — раздел 14.

## Краткая последовательность команд

Первый запуск на чистом сервере:

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

Обновление уже работающего сервера:

```bash
git fetch origin
git checkout services
git pull origin services
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
# Проверить сайт и /admin
# При необходимости назначить super_admin старому пользователю
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

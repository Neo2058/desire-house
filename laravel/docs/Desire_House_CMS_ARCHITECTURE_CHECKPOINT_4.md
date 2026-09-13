# Desire House CMS — Архитектурная контрольная точка 4.0

**Версия документа:** 4.0\
**Дата:** 12 сентября 2026\
**Ветка:** `services`\
**Коммит среза:** `575a8b0`\
**Предыдущая точка:** `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_3.md` (v3.0, 2 сентября 2026)

**Назначение:** зафиксировать текущий срез проекта, что изменилось после точки 3.0, и канонический порядок развёртывания сайта с CMS — как на новом сервере, так и при обновлении уже работающего продакшена.

Документ описывает только то, что есть в коде и что уже проверено на живом сервере.

Связанные документы:

| Документ | Роль |
| --- | --- |
| `docs/DEPLOYMENT_GUIDE.md` | Пошаговые команды: первый запуск и обновление |
| `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_2.md` | Публичные услуги и работы |
| `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_3.md` | SEO, GEO, пользователи админки |
| `docs/DEVELOPMENT_GUIDE.md` | ADR и стандарты разработки |

------------------------------------------------------------------------

# 1. Срез проекта на 12 сентября 2026

Desire House — CMS строительной компании на Laravel 13 + Filament 5 + Builder. Публичный сайт и админка живут в одном приложении. Контент страниц хранится в PostgreSQL, а не в Git.

## 1.1. Стек

| Слой | Факт |
| --- | --- |
| PHP | `^8.3` |
| Laravel | `^13.8` |
| Админка | Filament `^5.6`, Shield `^4.2` |
| БД | PostgreSQL, `jsonb` у `services.blocks` |
| Медиа | Spatie Media Library (`cover`, `gallery`) + модель `Image` |
| SEO | `ralphjsmit/laravel-seo`, `spatie/laravel-sitemap` |
| GEO | `/llms.txt`, `/llms-full.txt`, Schema.org |
| Frontend | Blade-блоки, собственный CSS; Vite собирает админские/вспомогательные ассеты |
| Права | Spatie Permission: роли `super_admin` и `panel_user` |

## 1.2. Что уже работает публично

| URL | Источник |
| --- | --- |
| `/` | `Page` со slug `home`, блоки Builder |
| `/uslugi` | `Page` со slug `uslugi` |
| `/uslugi/{slug}` | `Service` + `ServicePageTemplate` |
| `/raboty` | `ProjectIndexTemplate` |
| `/raboty/{slug}` | `Project` + `ProjectPageTemplate` |
| `POST /lead` | заявка |
| `/admin` | Filament CMS |
| `/setup` | мастер первого администратора |
| `/first-login/password` | смена временного пароля |
| `/sitemap.xml` | карта сайта |
| `/llms.txt`, `/llms-full.txt` | профиль для языковых моделей |

Порядок маршрутов в `routes/web.php` важен: `/raboty` объявлен **до** `/{slug?}`.

## 1.3. Два способа собрать страницу

Оба пути заканчиваются в `BuilderRenderer`.

``` text
Page.blocks (JSON в CMS)
        ↓
BuilderRenderer → Provider → Blade/CSS

Service / Project (поля модели)
        ↓
ServicePageTemplate / ProjectPageTemplate / ProjectIndexTemplate
        ↓
тот же BuilderRenderer
```

Свободные страницы (главная, каталог услуг, «О компании», «Контакты») собираются в админке блоками. Карточки услуги и проекта собирает шаблон сущности, чтобы не копировать один и тот же Hero/галерею в JSON каждой записи.

## 1.4. Админка

Группы меню:

``` text
Контент     Страницы, Услуги, Проекты
Продажи     Заявки
Медиа       Изображения, Галереи
Настройки   Сайт, Меню, Пользователи, Роли (Shield)
```

`HeroSectionResource` скрыт из навигации. Первый экран главной редактируется в **Страницы → блок Hero**.

Вход в панель требует роль `super_admin` или `panel_user` (`User::canAccessPanel()`). Пароли задаются в **Настройки → Пользователи**, не в Roles.

## 1.5. Где что живёт

| Живёт в Git | Живёт в PostgreSQL / storage |
| --- | --- |
| код, миграции, Blade, CSS, конфиги | страницы и их `blocks` |
| шаблоны услуг/работ | услуги, проекты, меню |
| маршруты, провайдеры Builder | `site_settings` (телефон, логотип, адрес) |
| SEO/GEO-код | таблица `seo`, медиафайлы |

`git pull` **не затирает** главную, меню и настройки сайта. Он меняет только код. Контент пропадает только от `migrate:fresh`, `db:seed`, ручного `DELETE` или порчи диска `storage`.

------------------------------------------------------------------------

# 2. Что обновилось после точки 3.0

Точка 3.0 закрыла SEO, GEO и пользователей. После неё на ветке `services` произошло следующее.

## 2.1. Код

Коммит `575a8b0` — главная больше не падает, если в CMS ещё нет записи настроек сайта.

Причина инцидента на продакшене 12 сентября 2026: после `php artisan migrate` таблица `site_settings` была пустая. `SiteSettingsProvider::make()` возвращал `[]`, а `HeroProvider` читал `$site['settings']` без ключа. В PHP 8 это `Undefined array key "settings"` и HTTP 500, даже при `?->`.

Исправление:

- `SiteSettingsProvider` всегда отдаёт ключи `settings`, `logo`, `favicon` (значения могут быть `null`);
- `HeroProvider`, `InnerHeroProvider`, `ServiceAboutProvider`, `ProjectAboutProvider` читают `$site['settings'] ?? null`.

Пустая таблица настроек больше не валит публичные страницы. Телефон и мессенджеры в шапке просто отсутствуют, пока запись не заполнят в **Настройки → Сайт**.

## 2.2. Админка (закрыто в 3.0, подтверждено на срезе)

- ресурс **Пользователи**: имя, email, пароль, роли, флаг смены пароля;
- Shield Roles в группе **Настройки**;
- пункт **Hero** скрыт.

## 2.3. Продакшен

Код ветки `services` выкатывался на уже существующий сайт. Зафиксированный экземпляр на дату среза:

``` text
хост     199.189.255.204
SSH      deeploy
каталог  /var/www/desire-house/laravel
ветка    services
```

После первого `migrate` на этом сервере появились таблицы `menu_items`, `site_settings`, `permissions`/`roles`, `must_change_password`, `seo`. Старый администратор остался в `users`, но роли ему ещё не было — Filament его не пускает, пока не назначен `super_admin`.

Этот сценарий (обновление живого сайта со старым админом) теперь канонический и описан ниже.

------------------------------------------------------------------------

# 3. Порядок развёртывания сайта с CMS

Есть два разных сценария. Их нельзя путать.

| Сценарий | Когда |
| --- | --- |
| А. Новый сервер | чистая машина, пустая база |
| Б. Обновление живого сайта | Git уже клонирован, в базе есть страницы и пользователи |

Полные команды — в `docs/DEPLOYMENT_GUIDE.md`. Здесь — порядок и смысл шагов.

## 3.1. Сценарий А — новый сервер

1. Установить PHP 8.3+, PostgreSQL, Composer, Node.js.
2. Клонировать репозиторий, `document root` → `laravel/public`.
3. Создать роль и базу PostgreSQL **не** под пользователем `postgres` приложения.
4. Скопировать `.env.example` → `.env`, задать `APP_URL`, `DB_*`, `APP_DEBUG=false`.
5. `composer install --no-dev --prefer-dist --optimize-autoloader`
6. `npm ci && npm run build`
7. `php artisan key:generate`
8. `php artisan migrate --force`
9. `php artisan storage:link`
10. `php artisan setup:token`
11. Открыть `/setup`, создать `super_admin`, сменить пароль на `/first-login/password`.
12. Выключить установку: `INSTALL_ENABLED=false`, очистить `INSTALL_TOKEN`.
13. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
14. Наполнить CMS (раздел 4). Пока нет опубликованной страницы `home`, `/` отдаёт контролируемый 404 — это нормально.

Не создавать фиктивную главную сидером. Страница `home` создаётся в Filament.

## 3.2. Сценарий Б — обновление уже работающего сайта

Это тот порядок, который нужно повторять при каждом выкате ветки `services` на прод.

Работать системным пользователем приложения (на текущем сервере — `deeploy`), из каталога `laravel`.

``` text
1. ssh deeploy@<хост>
2. cd /var/www/desire-house/laravel
3. git fetch origin
4. git checkout services
5. git pull origin services
6. composer install --no-dev --prefer-dist --optimize-autoloader
7. npm ci
8. npm run build
9. php artisan migrate --force
10. php artisan storage:link
11. php artisan optimize:clear
12. проверить сайт и /admin
13. при необходимости php artisan config:cache && php artisan route:cache && php artisan view:cache
```

После шага 9:

- если таблицы ролей только что появились — назначить `super_admin` существующему пользователю (раздел 3.4);
- если `site_settings` пустая — сайт не должен падать; заполнить **Настройки → Сайт** в админке;
- контент `pages.blocks` не трогать.

`php artisan optimize` на проде допустим **после** проверки, что страницы открываются. Если после него снова 500 — сразу `php artisan optimize:clear`: в кэше маршрутов/конфига может остаться старое состояние.

## 3.3. Что нельзя делать на живом сайте

``` text
php artisan migrate:fresh
php artisan migrate:fresh --seed
php artisan db:seed
php artisan db:wipe
```

Эти команды уничтожают страницы, заявки, медиа-привязки и пользователей.

Не пересобирать главную, «О компании» и «Контакты» в локальной базе «чтобы было как на проде». Это данные продакшена.

## 3.4. Старый администратор после появления ролей

Миграция `create_permission_tables` создаёт **таблицы**, не роли и не назначения.

Пока у пользователя нет `super_admin` или `panel_user`, `/admin` показывает форму входа, но пускает с ошибкой прав. Мастер `/setup` существующий email не примет (`unique:users`).

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

Пароль, если забыт:

```php
$user->password = 'НовыйПароль-НеКороткий!';
$user->must_change_password = false;
$user->save();
```

После входа сменить пароль в **Настройки → Пользователи**. Временный пароль из tinker в Git и в этот документ не записывать.

Если `super_admin` в базе ещё никому не назначен и нужно создать **нового** администратора с нуля — это сценарий А, команда `setup:token`, а не tinker.

## 3.5. Если после `git pull` сайт отдаёт 500

1. Смотреть `storage/logs/laravel.log`, не наугад перезапускать миграции.
2. Сообщение `Undefined array key "settings"` — нужен коммит `575a8b0` или новее, затем `php artisan optimize:clear`.
3. Сообщение про роли / `canAccessPanel` — раздел 3.4.
4. Не запускать `migrate:fresh`.

------------------------------------------------------------------------

# 4. Порядок наполнения CMS после кода

Код сам по себе не делает продающий сайт. Контент заполняется в Filament в таком порядке, потому что блоки ссылаются друг на друга.

``` text
1. Настройки → Сайт
   компания, телефон, email, Telegram, WhatsApp, адрес, логотип, favicon

2. Медиа → Изображения
   фото для Hero, услуг, проектов, логотипа

3. Контент → Услуги
   опубликованные услуги со slug (например stroitelstvo-domov)

4. Контент → Проекты
   работы, привязка к услугам, обложка, галерея

5. Настройки → Меню
   пункты шапки/подвала с URL /uslugi, /raboty и внутренними страницами

6. Контент → Страницы
   home     — главная (Hero, услуги, проекты, CTA, …)
   uslugi   — каталог услуг
   about / o-kompanii, contacts / kontakty — свободные страницы

7. SEO в карточках страниц, услуг и проектов
   title, description; иначе подставятся название записи и SiteSetting

8. Продажи → проверить заявку с публичной формы
```

Страницы услуги и проекта **не нужно** собирать блоками вручную: их рисуют `ServicePageTemplate` и `ProjectPageTemplate`. Extra-блоки в `services.blocks` можно добавить поверх шаблона, но это не обязательно для запуска.

После публикации:

``` text
/                  главная
/uslugi            каталог
/uslugi/{slug}     карточка услуги
/raboty            галерея работ по типам услуг
/raboty/{slug}     карточка проекта
/sitemap.xml       должен включать опубликованные URL
/llms.txt          должен подтянуть компанию и услуги из CMS
```

------------------------------------------------------------------------

# 5. Файловая карта среза

Новое или существенно затронутое после точки 3.0:

``` text
app/Platform/Builder/Providers/SiteSettingsProvider.php
app/Platform/Builder/Providers/HeroProvider.php
app/Platform/Builder/Providers/InnerHeroProvider.php
app/Platform/Builder/Providers/ServiceAboutProvider.php
app/Platform/Builder/Providers/ProjectAboutProvider.php
app/Filament/Admin/Resources/Users/
app/Filament/Admin/Resources/HeroSections/HeroSectionResource.php
docs/DEPLOYMENT_GUIDE.md
docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_4.md
```

Публичный контракт и SEO/GEO без изменений относительно точки 3.0.

------------------------------------------------------------------------

# 6. Что сознательно не делалось

1. Не собирались в локальной CMS главная, «О компании» и «Контакты» — это контент продакшена.
2. Не нормализовались Builder-блоки в отдельные сущности (этап 2 README).
3. Не подключались email/Telegram-уведомления по заявкам.
4. Не автоматизировался деплой скриптом CI/CD: выкат пока ручной по этому документу.

------------------------------------------------------------------------

# 7. Следующий этап

На проде: наполнить «О компании» и «Контакты», проверить меню и настройки сайта, убедиться, что старый админ входит с `super_admin`.

По коду, согласно roadmap после SEO/GEO:

``` text
уведомления по заявкам (email, Telegram)
```

Затем кэш, безопасность, тесты и, при необходимости, скрипт деплоя вместо ручных команд.

------------------------------------------------------------------------

# 8. Итог

К точке 4.0 публичный сайт услуг и работ, SEO/GEO и админка пользователей работают. Главная не падает без записи `site_settings`. Контент CMS не хранится в Git: `git pull` обновляет код и не стирает страницы. Развёртывание разделено на первый запуск (`setup:token`) и обновление живого сервера (`pull → composer → npm → migrate → optimize:clear`). Старому администратору после миграции ролей нужно явно выдать `super_admin`.

**Этот документ — контрольная точка 4.0.**

# Desire House CMS — Архитектурная контрольная точка 3.0

**Версия документа:** 3.0\
**Дата:** 2 сентября 2026\
**Ветка:** `services`\
**Предыдущая точка:** `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_2.md` (v2.0, 31 августа 2026)

**Назначение:** зафиксировать этап SEO, GEO и управления доступом в админке. Документ описывает только то, что реально есть в коде.

------------------------------------------------------------------------

# 1. Какой этап закрыт

После карточек услуг и работ закрыты:

1. публичное SEO (meta, Open Graph, canonical, Schema.org, sitemap, robots);
2. GEO для генеративных ИИ (`llms.txt`, `llms-full.txt`, расширенная микроразметка);
3. управление пользователями и паролями в Filament.

Сборка контента главной, «О компании» и «Контакты» в локальной CMS в этот этап не входит: это работа для продакшена.

------------------------------------------------------------------------

# 2. SEO

Используются пакеты, уже лежавшие в `composer.json`:

``` text
ralphjsmit/laravel-seo
spatie/laravel-sitemap
```

Вторая SEO-система не создавалась.

## 2.1. Данные

Таблица `seo` (morph к `Page`, `Service`, `Project`):

``` text
title
description
image
author
robots
canonical_url
```

Модели подключают `App\Support\HasPublicSeo`.

Порядок значений:

1. поля из админки (`seo.title`, `seo.description`), если заполнены;
2. название и описание записи;
3. запасной текст компании из `SiteSetting`.

Картинка Open Graph берётся из обложки услуги/проекта или логотипа сайта.

## 2.2. Публичный вывод

`resources/views/layouts/app.blade.php`:

``` blade
{!! seo($seo ?? null) !!}
```

Контроллеры передают модель или `SEOData`:

| Страница | Источник `$seo` |
| --- | --- |
| `/{slug}` | `Page` |
| `/uslugi/{slug}` | `Service` |
| `/raboty/{slug}` | `Project` |
| `/raboty` | `SiteSeo::worksIndexData()` |

На странице есть:

- `<title>` с суффиксом компании;
- meta description;
- canonical;
- Open Graph и Twitter Card;
- JSON-LD.

## 2.3. Админка

Секция **SEO** добавлена в формы:

``` text
Страницы
Услуги
Проекты
```

Файл: `app/Filament/Admin/Resources/Concerns/SeoFields.php`.

Отдельный SEO Resource не создавался.

## 2.4. Sitemap и robots

``` text
GET /sitemap.xml     SitemapController
php artisan sitemap:generate
```

В sitemap попадают опубликованные `Page`, `/raboty`, услуги и проекты.

`public/robots.txt`:

- закрыты `/admin`, `/setup`, `/first-login`;
- указан `/sitemap.xml`;
- явно разрешены GPTBot, ClaudeBot, PerplexityBot, Google-Extended и связанные боты.

------------------------------------------------------------------------

# 3. GEO

GEO не дублирует контент. Тексты для моделей собираются из тех же `SiteSetting`, `Service` и `Project`.

## 3.1. Файлы для языковых моделей

``` text
GET /llms.txt        LlmsTxtController@index
GET /llms-full.txt   LlmsTxtController@full
```

Код:

``` text
app/Platform/Geo/LlmsDocument.php
app/Http/Controllers/LlmsTxtController.php
```

`/llms.txt` — краткий указатель: компания, контакты, разделы, услуги, работы.

`/llms-full.txt` — полный профиль для цитирования.

В `<head>` добавлена ссылка:

``` html
<link rel="alternate" type="text/plain" href="/llms.txt" title="LLM information">
```

Контент страницы обёрнут в `<main>`.

## 3.2. Schema.org сверх базового LocalBusiness

На всех публичных страницах:

``` text
WebSite
HomeAndConstructionBusiness
  areaServed
  makesOffer → Service
```

Дополнительно:

| URL | Тип |
| --- | --- |
| `/uslugi` | ItemList услуг |
| `/raboty` | ItemList работ |
| `/uslugi/{slug}` | Service |
| `/raboty/{slug}` | CreativeWork |

ADR: `ADR-012` (SEO), `ADR-013` (GEO) в `DEVELOPMENT_GUIDE.md`.

------------------------------------------------------------------------

# 4. Админка: доступ

## 4.1. Пользователи

Появился ресурс:

``` text
Настройки → Пользователи
app/Filament/Admin/Resources/Users/
```

В карточке пользователя:

- имя, email;
- пароль при создании и смене (пустое поле на редактировании не затирает пароль);
- роли `super_admin` / `panel_user`;
- флаг «сменить пароль при следующем входе».

`Roles` из Filament Shield перенесён в группу **Настройки**. Пароли в Roles не живут: это роли и права.

Вход в панель по-прежнему требует роль `super_admin` или `panel_user` (`User::canAccessPanel()`).

## 4.2. Hero в навигации

`HeroSectionResource` скрыт:

``` php
protected static bool $shouldRegisterNavigation = false;
```

Ресурс не удалён. Рабочий первый экран редактируется в **Страницы → блок Hero**.

------------------------------------------------------------------------

# 5. Файловая карта этапа

``` text
app/Support/HasPublicSeo.php
app/Platform/Seo/SiteSeo.php
app/Platform/Seo/SitemapBuilder.php
app/Platform/Geo/LlmsDocument.php
app/Http/Controllers/SitemapController.php
app/Http/Controllers/LlmsTxtController.php
app/Console/Commands/GenerateSitemap.php
app/Filament/Admin/Resources/Concerns/SeoFields.php
app/Filament/Admin/Resources/Users/
config/seo.php
database/migrations/2026_08_31_120000_create_seo_table.php
tests/Feature/Seo/PublicSeoTest.php
```

------------------------------------------------------------------------

# 6. Что сознательно не делалось

1. Не собирались в локальной CMS главная, «О компании» и «Контакты».
2. Не создавался отдельный SEO/GEO Resource.
3. Не нормализовались Builder-блоки в отдельные сущности.
4. Не подключались email/Telegram-уведомления по заявкам.

------------------------------------------------------------------------

# 7. Следующий этап (не начат)

По roadmap после SEO/GEO:

``` text
уведомления по заявкам (email, Telegram)
```

Затем production: кэш, безопасность, тесты, деплой.

Блог, FAQ и отзывы по-прежнему отдельные модули.

------------------------------------------------------------------------

# 8. Итог

Публичные страницы отдают поисковые теги и машиночитаемый профиль для ИИ. Контент для GEO берётся из CMS. В админке можно создавать пользователей и менять пароли.

**Этот документ — контрольная точка 3.0.**

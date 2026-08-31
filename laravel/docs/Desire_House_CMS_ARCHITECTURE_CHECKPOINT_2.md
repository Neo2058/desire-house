# Desire House CMS — Архитектурная контрольная точка 2.0

**Версия документа:** 2.0\
**Дата:** 31 августа 2026\
**Ветка:** `services`\
**Предыдущая точка:** `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT.md` (v1.0, 14 августа 2026)

**Назначение:** зафиксировать завершённый этап публичного frontend для услуг и работ. Документ описывает только то, что реально есть в коде.

> Этот документ не заменяет контрольную точку 1.0. Он снимает с неё статус «следующий шаг — ServicesBlock» и фиксирует новую каноническую схему публичных страниц сущностей.

------------------------------------------------------------------------

# 1. Какой этап закрыт

Закрыт этап **публичных страниц услуг и портфолио**.

Пользователь может:

1.  открыть каталог услуг;
2.  перейти в карточку услуги;
3.  открыть каталог работ, сгруппированный по услугам;
4.  перейти в карточку проекта с описанием и галереей;
5.  оставить заявку с этих страниц.

Это соответствует этапу 2 roadmap из `DEVELOPMENT_GUIDE.md` в части:

``` text
Услуги
Проекты
```

Главная страница, блог, о компании и контакты в этот этап не входят.

------------------------------------------------------------------------

# 2. Публичный URL-контракт (фактический)

Реализовано и работает:

| URL | Назначение | Источник |
| --- | --- | --- |
| `/` | Главная | `Page` slug `home` |
| `/uslugi` | Каталог услуг | `Page` slug `uslugi` |
| `/uslugi/{service:slug}` | Страница услуги | `ServiceController` + `ServicePageTemplate` |
| `/raboty` | Каталог работ | `ProjectIndexController` + `ProjectIndexTemplate` |
| `/raboty/{project:slug}` | Страница проекта | `ProjectController` + `ProjectPageTemplate` |
| `POST /lead` | Заявка | `LeadController` |

Порядок маршрутов в `routes/web.php` важен:

``` text
POST /lead
GET  /uslugi/{service:slug}
GET  /raboty
GET  /raboty/{project:slug}
GET  /{slug?}
```

`/raboty` объявлен **до** `/{slug?}`. Иначе каталог работ снова станет 404 через `PageController`.

Не реализованы как публичные страницы:

``` text
/blog
/o-kompanii
/kontakty
```

------------------------------------------------------------------------

# 3. Два способа собрать публичную страницу

После этого этапа в проекте два канонических пути. Они оба заканчиваются одним и тем же runtime:

``` text
BuilderRenderer
    ↓
Provider
    ↓
Blade блока
    ↓
CSS / JS блока
```

## 3.1. CMS Page — хранимый JSON

Используется для свободных страниц:

``` text
Page.blocks  →  PageController  →  pages.page
```

Так собраны:

``` text
/
/uslugi
```

Каталог `/uslugi` — это запись `Page` со slug `uslugi`. Типичный набор блоков:

``` text
inner_hero
services_grid
footer
```

## 3.2. Шаблоны сущностей — runtime JSON

Используется для карточек, у которых есть своя модель:

``` text
ServicePageTemplate     →  /uslugi/{slug}
ProjectPageTemplate     →  /raboty/{slug}
ProjectIndexTemplate    →  /raboty
```

Файлы:

``` text
app/Platform/Builder/Support/ServicePageTemplate.php
app/Platform/Builder/Support/ProjectPageTemplate.php
app/Platform/Builder/Support/ProjectIndexTemplate.php
app/Platform/Builder/Support/SiteChrome.php
```

Шаблоны **не рисуют HTML сами**. Они собирают массив блоков той же формы, что хранит CMS:

``` text
[
    'type' => '...',
    'data' => [...],
]
```

и отдают его в `BuilderRenderer`.

Это позволяет:

-   держать карточку услуги/проекта всегда целой, даже если в CMS пустой `blocks`;
-   не дублировать шапку, футер и CTA в JSON каждой записи;
-   оставить Filament Builder для дополнительных секций услуги.

`SiteChrome` — общий футер и CTA для шаблонов. Источник: `SiteSetting` + `MenuItem`, не хардкод контактов.

------------------------------------------------------------------------

# 4. Состав шаблонов

## 4.1. Страница услуги

``` text
inner_hero
service_about
[дополнительные Service.blocks, кроме chrome]
projects          mode=current_service
services_grid     mode=all, exclude_current
cta
footer
```

Chrome, который шаблон считает своим и выкидывает из extras:

``` text
hero
inner_hero
service_about
footer
```

Дополнительные блоки из админки (галерея, сравнение, features и т.д.) вставляются после описания.

## 4.2. Страница проекта

``` text
inner_hero
project_about
project_gallery
projects          mode=all, exclude_current
cta
footer
```

У `Project` нет поля `blocks`. Карточка проекта целиком определяется шаблоном.

## 4.3. Каталог работ `/raboty`

``` text
inner_hero
works_gallery
cta
footer
```

`works_gallery` группирует опубликованные проекты по опубликованным услугам через `project_service`. Проекты без услуги попадают в группу «Другие работы».

------------------------------------------------------------------------

# 5. Контроллеры

``` text
PageController            /{slug?}
ServiceController         /uslugi/{service:slug}
ProjectIndexController    /raboty
ProjectController         /raboty/{project:slug}
LeadController            POST /lead
```

`ProjectIndexController` соответствует имени из `DEVELOPMENT_GUIDE.md`.

Неопубликованные `Service` и `Project` отдают 404.

------------------------------------------------------------------------

# 6. Общая шапка

Шапка больше не живёт только внутри Hero главной.

``` text
resources/views/components/site-header.blade.php
```

Подключена в:

``` text
blocks/hero.blade.php
blocks/inner_hero.blade.php
```

Данные шапки по-прежнему даёт связка:

``` text
SiteSettingsProvider
MenuProvider
```

`InnerHeroProvider` отдаёт те же `logo`, `menu`, `phone`, `telegram`, `whatsapp`, что и `HeroProvider`.

Внутренние страницы без шапки больше не считаются допустимыми.

------------------------------------------------------------------------

# 7. Модель Service

`Service` реализует Spatie Media Library:

``` text
collection: cover
singleFile()
```

В админке:

``` text
ServiceForm     SpatieMediaLibraryFileUpload cover
ServicesTable   SpatieMediaLibraryImageColumn cover
```

`blocks` добавлен в `$fillable`. Casts:

``` text
blocks         array
is_published   boolean
is_featured    boolean
```

В fillable по-прежнему есть `short_description` и `sort_order`, колонок в текущих миграциях нет. Это наследие модели, не часть закрытого этапа.

Связь с проектами без изменений:

``` text
Service belongsToMany Project  через project_service
```

------------------------------------------------------------------------

# 8. Блоки, появившиеся на этом этапе

## В Filament Registry

Добавлен:

``` text
service_about     ServiceAboutBlock
```

`ServicesGridBlock` расширен режимами выбора, как `ServicesBlock`:

``` text
all
featured
manual
limit
```

`InnerHeroBlock` умеет родителя в крошках:

``` text
breadcrumb_parent_title
breadcrumb_parent_url
```

## Только runtime (нет схемы в Registry)

Эти типы существуют как Provider + Blade + CSS. Они нужны шаблонам сущностей и не предлагаются редактору на свободной Page, потому что без текущей модели пусты:

``` text
project_about
project_gallery
works_gallery
```

Регистрировать их в `BuilderRegistry` без отдельного решения нельзя.

------------------------------------------------------------------------

# 9. BuilderRenderer

Фактические специализированные типы:

``` text
hero
services
services_grid
projects
gallery
stats
footer
inner_hero
service_about
project_about
project_gallery
works_gallery
```

`features`, `comparison`, `cta` по-прежнему идут в `default` и получают `['block' => $block['data']]`.

`ServicesProvider` и `ProjectsProvider` принимают текущую модель и умеют:

``` text
exclude_current
```

`InnerHeroProvider` берёт фон в порядке:

1.  `background_image_id` → модель `Image`;
2.  `background_url` из данных блока;
3.  `cover` текущей модели, если она `HasMedia`.

------------------------------------------------------------------------

# 10. Frontend-представление

Публичный CSS по-прежнему компонентный, не Tailwind.

Новые файлы стилей:

``` text
resources/css/blocks/service-about.css
resources/css/blocks/project-about.css
resources/css/blocks/project-gallery.css
resources/css/blocks/works-gallery.css
```

Новый JS:

``` text
resources/js/service-about.js
resources/js/project-gallery.js
```

Карточки работ на `/raboty` используют существующий `.project-card`. Галерея внутри проекта — отдельная сетка с лайтбоксом.

Визуальный контракт внутренних страниц:

``` text
тёмный inner-hero
светлый блок описания (#f7f7f7)
тёмные проекты / галерея / CTA / футер
акцент #ef6b1d
оранжевая линия над заголовком секции
```

Компактный блок главной `services` **не закрыт**:

``` text
resources/views/blocks/services.blade.php   базовая разметка
resources/css/blocks/services.css           пуст
```

Каталог услуг использует `services_grid`, не `services`.

------------------------------------------------------------------------

# 11. Источники заявок

Форма CTA читает `source` из блока.

Фактические значения на новых страницах:

``` text
service    страница услуги
project    страница проекта
works      каталог /raboty
cta        значение по умолчанию
hero       модальное окно из layout
```

Отдельный POST-endpoint по-прежнему один: `/lead`.

------------------------------------------------------------------------

# 12. Что сознательно не делалось

1.  Не создавался второй Builder и второй набор провайдеров услуг.
2.  У `Project` не появлялось JSON-поле `blocks`.
3.  Runtime-блоки проекта не вынесены в Filament Registry.
4.  Не создавалась CMS-страница slug `raboty`: каталог работ — сущность портфолио, не свободный Page.
5.  Шапка не выносилась в `layouts/app.blade.php`, чтобы не задвоить её с Hero главной.
6.  Публичный frontend не переводился на Tailwind.
7.  Не закрывались Blog, FAQ, Testimonials, SEO-модуль, страница «О компании», страница «Контакты».
8.  Не стилизовался компактный `ServicesBlock` для главной.

------------------------------------------------------------------------

# 13. Правила, которые остаются в силе

Из контрольной точки 1.0 и ADR проекта:

1.  Builder — единственный механизм рендера контентных секций.
2.  Blade не делает сложные Eloquent-запросы.
3.  Изображения сущностей — только Media Library.
4.  Не нормализовать JSON-блоки в отдельные таблицы до конца frontend-этапа.
5.  Не плодить параллельные архитектуры, если уже есть Provider и шаблон.

Новое правило этого этапа:

6.  Карточка модели (`Service`, `Project`) собирается шаблоном в `Support/`. Свободная страница (`Page`) собирается JSON из CMS. Смешивать эти роли без причины нельзя.

------------------------------------------------------------------------

# 14. Фактическая карта публичного сайта

``` text
Главная            /                      Page home
Каталог услуг      /uslugi                Page uslugi
Услуга             /uslugi/{slug}         ServicePageTemplate
Каталог работ      /raboty                ProjectIndexTemplate
Проект             /raboty/{slug}         ProjectPageTemplate
Заявка             POST /lead             Lead
```

Навигация между ними:

``` text
/uslugi  →  карточка услуги  →  связанные проекты
/raboty  →  группа услуги    →  карточка проекта  →  услуга
```

------------------------------------------------------------------------

# 15. Следующий этап (не начат)

После этой точки логичный фронтенд-порядок:

1.  Компактный блок `services` на главной (пустой CSS).
2.  Страницы «О компании» и «Контакты» как `Page`.
3.  Список `/raboty` в меню CMS, если его ещё нет как `MenuItem`.
4.  Блог / FAQ — только после отдельного проектирования.

Не начинать нормализацию Builder в отдельные сущности, пока не закрыт этап 1 (рабочий frontend всего сайта).

------------------------------------------------------------------------

# 16. Итог

Этап услуг и работ на публичной части считается завершённым.

Канон рендера не сломан: Filament задаёт контент, BuilderRenderer собирает секции, Blade/CSS их показывают.

Добавлен слой шаблонов сущностей в `Platform/Builder/Support`. Он закрывает пустые карточки услуг и сырой HTML проектов, не вводя второй конструктор страниц.

**Этот документ — контрольная точка 2.0. Дальнейшая разработка публичных страниц должна отталкиваться от него и от точки 1.0 совместно.**

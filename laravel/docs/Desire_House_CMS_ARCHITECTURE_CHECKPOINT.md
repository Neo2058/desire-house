# Desire House CMS --- Архитектурная контрольная точка

**Версия документа:** 1.1\
**Первичный архитектурный анализ:** 14 августа 2026\
**Последняя сверка с кодом:** 24 августа 2026\
**Назначение:** зафиксировать фактическую архитектуру проекта на момент
анализа загруженного архива и использовать этот документ как исходную
точку дальнейшей разработки.

> **Статус:** историческая контрольная точка. Этап публичных страниц услуг
> и работ закрыт документом
> `docs/Desire_House_CMS_ARCHITECTURE_CHECKPOINT_2.md` (31 августа 2026).
> Разделы 54–65 ниже описывают план на тот момент, а не текущее состояние.

> **Важно:** этот документ описывает прежде всего то, что реально
> присутствует в архиве проекта. Если документация проекта, имена файлов
> или планы расходятся с фактическим кодом, ниже это явно отмечено.
> Предположения не выдаются за реализованный функционал.

## Команда и распределение ответственности

Проект развивается небольшой продуктовой командой с совмещением нескольких
функций. Указанные ниже роли описывают зоны ответственности и вклад в проект,
но сами по себе не являются заявлением о трудоустройстве отдельных людей.

| Роль | Участник / формат | Зона ответственности |
|---|---|---|
| Ведущий инженер-разработчик | Владелец проекта | Реализация backend и frontend, интеграция Laravel и Filament, работа с данными, тестирование, принятие инженерных решений и поставка результата. |
| Архитектор и технический партнёр | Codex (AI, OpenAI) | Анализ существующего кода, проектирование и проверка архитектурных границ, техническое ревью, выявление рисков, сопровождение рефакторинга и актуализация документации. |
| Бизнес- и системный анализ | Функциональная роль команды | Формализация требований, пользовательских сценариев, CMS-процессов и критериев приёмки. На текущем этапе функция распределена между ведущим инженером и AI-архитектором. |
| UX/UI-дизайн | Функциональная роль команды | Проработка интерфейсных сценариев, визуальной системы и согласованности публичного сайта с CMS. На текущем этапе функция выполняется в рамках продуктовой разработки; отдельный специалист не заявлен. |

Ключевые технические роли принадлежат ведущему инженеру-разработчику и
AI-архитектору. Авторство коммитов, кода и проектных решений при необходимости
подтверждается историей Git; этот раздел фиксирует распределение функций, а не
заменяет журнал изменений.

## Статус актуализации 1.1

24 августа 2026 года документ повторно сверен с веткой `services` и текущими
манифестами зависимостей. Подтверждены Laravel `^13.8`, Filament `^5.6`, PHP
`^8.3`, Vite `^8.0.0`, Tailwind CSS `^4.0.0` и Swiper `^14.0.1`.

Маршрут `/uslugi/{service:slug}` и рендеринг `Service.blocks` через
`BuilderRenderer` присутствуют. `resources/views/blocks/services.blade.php`
реализован пока как базовый структурный шаблон, а
`resources/css/blocks/services.css` на момент первичной сверки оставался
пустым. В рамках этапа Services он получил адаптивное оформление карточек, а
шаблон — переходы к опубликованным услугам и изображения связанных работ.
Страница услуги дополнена резервным выводом связанных проектов и кнопкой
заказа, открывающей общую форму заявки с автоматически выбранной услугой.
Дальнейшая задача этапа 3 — визуальная проверка на реальном контенте и
последующая унификация деталей UI.

Автоматизация первичного развёртывания, создание первого администратора и
управление пользователями/ролями выделены в отдельный исполняемый backlog:
`docs/DEPLOYMENT_AND_ACCESS_AUTOMATION_BACKLOG.md`. До выполнения этапа
`ACCESS-01` безусловный доступ из `User::canAccessPanel()` считается критическим
долгом и не должен переноситься в production без ограничения.

На этапе первого входа пользователь с `must_change_password=true` не получает
доступ к экранам Filament. Аутентифицированная часть панели перенаправляет его
на `/first-login/password`; после проверки временного пароля и сохранения
нового стойкого пароля флаг снимается и доступ к `/admin` открывается.

------------------------------------------------------------------------

# 1. Резюме проекта

Desire House --- CMS и сайт строительной компании.

Проект построен вокруг трёх основных технических слоёв:

1.  **Laravel** --- backend, модели, маршрутизация, контроллеры, БД.
2.  **Filament** --- административная панель CMS.
3.  **Собственный Builder** --- конструктор страниц на основе
    JSON-блоков, которые редактируются через Filament Builder и затем
    рендерятся Blade-шаблонами.

Дополнительно существует отдельный модуль:

``` text
app/CMS/Dashboard/
```

который реализует собственный Dashboard поверх Filament.

Главная архитектурная идея проекта:

``` text
Filament
   │
   ├── CMS Resources
   │
   └── Dashboard
         │
         └── CMS Dashboard

Page / Service
   │
   └── blocks JSON
          │
          ▼
   BuilderRegistry
          │
          ▼
   Builder block schemas
          │
          ▼
   BuilderRenderer
          │
          ▼
   Providers
          │
          ▼
   Blade block views
          │
          ▼
   CSS / JavaScript
```

------------------------------------------------------------------------

# 2. Технологический стек

## Backend

Из `composer.json`:

-   PHP `^8.3`
-   Laravel `^13.8`
-   Filament `^5.6`
-   Livewire Volt `^1.10`
-   Laravel Tinker
-   Filament Shield
-   Spatie Media Library
-   Spatie Settings
-   Spatie Sitemap
-   Ralph J. Smit Laravel SEO

Фактическая разработка проекта также использует PostgreSQL-специфичную
миграцию для `jsonb`, поэтому документация проекта, где указан MySQL,
уже не соответствует текущему коду.

## Frontend

-   Blade
-   JavaScript ES modules
-   Vite
-   Tailwind CSS 4 установлен как зависимость
-   собственный CSS для публичного сайта
-   Swiper для проектов
-   IntersectionObserver для анимаций
-   AlpineJS в текущем `package.json` явно не присутствует, несмотря на
    старую запись в `DEVELOPMENT_GUIDE.md`

## Admin

-   Filament 5
-   собственный `AdminPanelProvider`
-   собственные Resources
-   собственный Dashboard
-   собственные CSS-стили Dashboard

## Media

Используются:

-   `spatie/laravel-medialibrary`
-   Filament Spatie Media Library Plugin

При этом часть Builder-блоков также использует обычный `FileUpload`,
поэтому система работы с медиа пока не полностью унифицирована.

------------------------------------------------------------------------

# 3. Фактическая структура приложения

Основные домены:

``` text
app/
├── CMS/
│   └── Dashboard/
│
├── Enums/
│
├── Filament/
│   └── Admin/
│       ├── Pages/
│       ├── Resources/
│       └── Widgets/
│
├── Http/
│   ├── Controllers/
│   └── Requests/
│
├── Models/
│
├── Platform/
│   └── Builder/
│
└── Providers/
```

Публичный frontend:

``` text
resources/
├── css/
│   ├── blocks/
│   ├── components/
│   ├── base.css
│   ├── layout.css
│   ├── footer.css
│   └── app.css
│
├── js/
│
└── views/
    ├── blocks/
    ├── builder/
    ├── cms/
    ├── components/
    ├── filament/
    ├── layouts/
    ├── pages/
    └── projects/
```

------------------------------------------------------------------------

# 4. Административная архитектура Filament

Точка входа:

``` text
app/Providers/Filament/AdminPanelProvider.php
```

Панель:

``` php
->id('admin')
->path('admin')
->brandName('Desire House CMS')
->login()
```

То есть административная часть находится по:

``` text
/admin
```

Resources автоматически обнаруживаются:

``` php
->discoverResources(
    in: app_path('Filament/Admin/Resources'),
    for: 'App\Filament\Admin\Resources'
)
```

Pages:

``` php
->discoverPages(
    in: app_path('Filament/Admin/Pages'),
    for: 'App\Filament\Admin\Pages'
)
```

Основной цвет Filament:

``` php
'primary' => Color::Orange
```

Подключён favicon:

``` php
->favicon(asset('favicon.ico'))
```

Используется Inter:

``` php
->font('Inter')
```

Для Dashboard CSS подключён отдельный render hook:

``` php
PanelsRenderHook::STYLES_AFTER
```

который подключает:

``` text
resources/css/components/stats-blade.css
```

Это важное архитектурное решение:

**CSS публичного сайта и CSS Dashboard разделены.**

------------------------------------------------------------------------

# 5. Фактическая структура навигации CMS

На момент архива реально присутствуют следующие Resources.

## Контент

``` text
Контент
├── Страницы
├── Услуги
├── Проекты
└── Hero
```

## Медиа

``` text
Медиа
├── Галереи
└── Изображения
```

## Продажи

``` text
Продажи
└── Заявки
```

## Настройки

``` text
Настройки
├── Меню
└── Сайт
```

Также присутствует `ComparisonSectionResource`, но он не оформлен как
полноценный элемент текущей навигационной структуры.

------------------------------------------------------------------------

# 6. Планируемая архитектура CMS

В предыдущей архитектурной концепции определена более крупная структура:

``` text
Dashboard

Контент
├── Страницы
├── Услуги
├── Проекты
├── Блог
├── FAQ
└── Медиа

Маркетинг
├── Отзывы
├── SEO
├── Редиректы
└── Локальные страницы

Продажи
├── Заявки
├── Источники
└── Аналитика

Настройки
├── Общие
├── Контакты
├── Соцсети
└── Сайт

Система
├── Пользователи
├── Роли
└── Права
```

Это **целевой план**, а не полностью реализованная структура.

------------------------------------------------------------------------

# 7. CMS Core: модели

## Page

Файл:

``` text
app/Models/Page.php
```

Поля:

``` text
id
title
slug
blocks
is_published
timestamps
```

`blocks`:

``` php
protected $casts = [
    'blocks' => 'array',
];
```

Page является главным владельцем Builder-контента публичных страниц.

------------------------------------------------------------------------

## Service

Файл:

``` text
app/Models/Service.php
```

Поля:

``` text
id
title
slug
short_description
description
is_published
is_featured
sort_order
blocks
timestamps
```

Связь:

``` php
public function projects(): BelongsToMany
```

с:

``` text
Project
```

Через:

``` text
project_service
```

`blocks` приводится к массиву через `casts()`.

### Важная особенность

В модели нет обычного `$fillable` для `blocks`, но присутствует:

``` php
protected function casts(): array
{
    return [
        'blocks' => 'array',
    ];
}
```

Таким образом, `blocks` является частью модели, но его массовое
заполнение необходимо учитывать отдельно.

------------------------------------------------------------------------

## Project

Файл:

``` text
app/Models/Project.php
```

Поля:

``` text
title
slug
city
area
short_description
description
is_featured
is_published
```

Media collections:

``` text
cover
gallery
```

`cover`:

``` text
singleFile()
```

Связь:

``` text
Project belongsToMany Service
```

------------------------------------------------------------------------

## Lead

Файл:

``` text
app/Models/Lead.php
```

Поля:

``` text
name
phone
object_type
message
source
status
processed
admin_comment
```

`processed`:

``` text
boolean
```

Lead используется одновременно:

-   публичной формой;
-   CRM Resource;
-   Dashboard;
-   статистикой.

------------------------------------------------------------------------

## SiteSetting

Файл:

``` text
app/Models/SiteSetting.php
```

Поля:

``` text
company_name
phone
email
telegram
whatsapp
address
logo_image_id
favicon_image_id
```

Связи:

``` text
logo()
favicon()
```

обе ведут к:

``` text
Image
```

------------------------------------------------------------------------

## Image

Файл:

``` text
app/Models/Image.php
```

Поля:

``` text
title
slug
alt
```

Media collection:

``` text
image
```

Accessor:

``` php
getUrlAttribute()
```

возвращает:

``` php
getFirstMediaUrl('image')
```

Таким образом:

``` php
$image->url
```

используется Builder-провайдерами.

------------------------------------------------------------------------

## Gallery

Поля:

``` text
title
slug
description
is_published
```

Media collection:

``` text
gallery
```

------------------------------------------------------------------------

## MenuItem

Поля:

``` text
title
url
page_id
sort
is_active
```

Связь:

``` text
page()
```

Accessor:

``` text
resolved_url
```

Если выбран Page:

``` text
home → /
other → /{slug}
```

Это используется `MenuProvider`.

------------------------------------------------------------------------

## Остальные модели

В проекте также существуют:

``` text
Faq
Post
PostCategory
Testimonial
HeroSection
ComparisonSection
```

Однако на момент анализа они реализованы значительно слабее основных
сущностей.

Особенно:

``` text
Faq
Post
PostCategory
Testimonial
```

содержат фактически пустые модели.

Это означает, что их следует считать **заготовками будущих
CMS-модулей**.

------------------------------------------------------------------------

# 8. Dashboard Architecture

Dashboard вынесен в отдельный домен:

``` text
app/CMS/Dashboard/
```

Структура:

``` text
CMS/Dashboard/
├── Contracts/
│   └── DashboardWidget.php
├── Registry/
│   └── DashboardRegistry.php
├── Services/
│   └── DashboardService.php
└── Widgets/
    ├── AbstractWidget.php
    ├── WelcomeWidget.php
    ├── StatsWidget.php
    ├── RecentLeadsWidget.php
    └── QuickActionsWidget.php
```

## Контракт

``` text
DashboardWidget
```

предполагает:

``` text
key()
title()
visible()
data()
```

Однако текущие конкретные Widgets наследуются от:

``` text
AbstractWidget
```

а не непосредственно реализуют интерфейс.

Это допустимо как текущая архитектура, но интерфейс и AbstractWidget
пока не связаны через `implements DashboardWidget`.

------------------------------------------------------------------------

# 9. DashboardRegistry

``` text
DashboardRegistry
```

хранит классы виджетов:

``` php
protected array $widgets = [];
```

Регистрация:

``` php
register(string $widget)
```

Получение:

``` php
all()
```

с удалением дубликатов.

------------------------------------------------------------------------

# 10. DashboardService

`DashboardService::boot()` регистрирует:

``` text
WelcomeWidget
StatsWidget
RecentLeadsWidget
QuickActionsWidget
```

Затем:

``` php
widgets()
```

возвращает Registry.

Это означает, что Dashboard уже имеет задел для расширяемой системы
виджетов.

------------------------------------------------------------------------

# 11. Dashboard Widgets

## WelcomeWidget

Показывает:

``` text
имя текущего пользователя
Desire House CMS
текущую дату
```

Имя берётся через:

``` php
Auth::user()?->name
```

------------------------------------------------------------------------

## StatsWidget

Реальные данные:

``` text
Page::count()
Service::count()
Project::count()
Lead::count()
```

Каждая статистика содержит:

``` text
label
value
icon
href
```

То есть статистика является не только информационной карточкой, но и
ссылкой на соответствующий Resource.

------------------------------------------------------------------------

## RecentLeadsWidget

Берёт:

``` text
последние 5 Lead
```

Для каждой заявки формирует:

``` text
id
name
phone
object_type
created_at
initials
href
```

------------------------------------------------------------------------

## QuickActionsWidget

Сейчас:

``` text
Создать страницу
Добавить услугу
Добавить проект
```

Ссылки получают URL непосредственно из Filament Resources.

Это хорошее решение: Dashboard не знает URL вручную.

------------------------------------------------------------------------

# 12. Page Dashboard

Файл:

``` text
app/Filament/Admin/Pages/Dashboard.php
```

Page напрямую использует:

``` text
DashboardService
```

В `mount()`:

``` text
DashboardService
    ↓
boot()
    ↓
widgets()
    ↓
$page->widgets
```

Blade:

``` text
resources/views/filament/admin/pages/dashboard.blade.php
```

перебирает зарегистрированные виджеты и:

1.  создаёт instance;
2.  получает `data()`;
3.  получает `view()`;
4.  подключает соответствующий Blade.

Таким образом Dashboard не содержит разметку конкретных карточек.

------------------------------------------------------------------------

# 13. Builder --- главный механизм контентных страниц

Builder находится:

``` text
app/Platform/Builder/
```

Структура:

``` text
Builder/
├── Blocks/
├── Providers/
├── Registry/
└── Renderers/
```

В текущем архиве также присутствуют пустые/зарезервированные каталоги:

``` text
DTO/
Exceptions/
Support/
Views/
```

Они пока не формируют самостоятельный работающий слой.

------------------------------------------------------------------------

# 14. Builder Block

Главный принцип:

``` text
Block PHP
    +
Schema
    +
Blade
    +
CSS
    +
JavaScript при необходимости
```

В текущей реализации PHP Block одновременно содержит:

-   идентификатор;
-   название;
-   Filament schema.

Например:

``` php
Block::make('services')
```

------------------------------------------------------------------------

# 15. BaseBlock

`BaseBlock` предоставляет общие поля:

``` text
blockName()
title()
subtitle()
description()
buttonText()
buttonUrl()
```

и группы:

``` text
contentSection()
settingsSection()
buttonSection()
section()
```

Это попытка стандартизировать схемы Builder-блоков.

------------------------------------------------------------------------

# 16. BuilderRegistry

Файл:

``` text
app/Platform/Builder/Registry/BuilderRegistry.php
```

Зарегистрированные блоки:

``` text
Hero
InnerHero
Features
Stats
Services
Gallery
Projects
Comparison
CTA
Footer
ServicesGrid
```

Именно этот Registry используется в Filament `PageForm` и `ServiceForm`.

------------------------------------------------------------------------

# 17. Фактический набор Builder-блоков

``` text
hero
inner_hero
features
stats
services
gallery
projects
comparison
cta
footer
services_grid
```

------------------------------------------------------------------------

# 18. Hero

Schema:

``` text
block_name
title
subtitle
description
background_image_id
person_image_id
button_text
button_url
```

Provider:

``` text
HeroProvider
```

добавляет:

``` text
background
person
logo
favicon
settings
phone
telegram
whatsapp
email
menu
```

Таким образом Hero фактически является не только визуальным блоком, но и
точкой вывода глобальной информации сайта.

------------------------------------------------------------------------

# 19. Inner Hero

Тип:

``` text
inner_hero
```

Schema:

``` text
block_name
title
subtitle
background_image_id
show_breadcrumbs
```

Provider:

``` text
InnerHeroProvider
```

------------------------------------------------------------------------

# 20. Features

Тип:

``` text
features
```

Schema содержит Repeater:

``` text
items[]
├── icon
├── title
└── description
```

Для иконок используется обычный:

``` text
FileUpload
```

------------------------------------------------------------------------

# 21. Stats

Тип:

``` text
stats
```

Schema:

``` text
block_name
title
subtitle
description
background_image_id
person_image_id
signature_image_id
items[]
    ├── number
    └── label
```

Provider:

``` text
StatsProvider
```

добавляет:

``` text
background
person
signature
```

------------------------------------------------------------------------

# 22. Projects

Тип:

``` text
projects
```

Поддерживает режимы:

``` text
all
featured
manual
current_service
```

`current_service` особенно важен:

``` text
Service
   ↓
projects()
   ↓
published Projects
```

Это позволяет использовать Builder одного типа как на обычных страницах,
так и на странице конкретной услуги.

------------------------------------------------------------------------

# 23. Gallery

Тип:

``` text
gallery
```

Schema:

``` text
gallery
columns
lightbox
```

Provider:

``` text
GalleryProvider
```

получает:

``` text
Gallery::find(...)
```

и передаёт модель в Blade.

------------------------------------------------------------------------

# 24. Comparison

Тип:

``` text
comparison
```

Содержит:

``` text
left_title
left_background
left_items[]
right_title
right_background
right_items[]
```

В текущей реализации фон загружается через обычный:

``` text
FileUpload
```

а не через единый Image/Media abstraction.

------------------------------------------------------------------------

# 25. CTA

Тип:

``` text
cta
```

Содержит:

``` text
title
subtitle
description
button_text
background
object_types
```

Используется форма заявки.

------------------------------------------------------------------------

# 26. Footer

Тип:

``` text
footer
```

Содержит:

``` text
company
logo_id
description
phone
email
telegram
whatsapp
copyright
privacy_url
menu[]
```

Provider:

``` text
FooterProvider
```

получает URL логотипа.

------------------------------------------------------------------------

# 27. ServicesBlock --- ключевой текущий участок

Текущий `ServicesBlock` находится:

``` text
app/Platform/Builder/Blocks/ServicesBlock.php
```

Schema уже поддерживает:

``` text
mode
```

варианты:

``` text
all
featured
manual
```

и:

``` text
services[]
limit
```

Также:

``` text
title
subtitle
button_text
button_url
```

Это означает, что **логика выбора услуг уже реализована**.

Provider:

``` text
ServicesProvider
```

работает с:

``` text
Service::query()
```

и поддерживает:

``` text
all
featured
manual
```

### Критически важный вывод

Для `ServicesBlock` **не нужно создавать новый механизм выбора услуг**.

В текущей архитектуре уже есть:

``` text
ServicesBlock
        ↓
ServicesProvider
        ↓
Service model
        ↓
resources/views/blocks/services.blade.php
```

Проблема текущего блока находится прежде всего в presentation layer.

Текущий Blade:

``` text
resources/views/blocks/services.blade.php
```

содержит только базовую разметку:

``` text
section
h2
subtitle
article
h3
description
```

и не имеет собственного класса CSS.

При этом:

``` text
resources/css/blocks/services.css
```

существует, но в архиве фактически пуст.

Следовательно:

**ServicesBlock уже имеет CMS-логику, но не имеет законченного frontend
presentation layer.**

Это и является ближайшей задачей.

------------------------------------------------------------------------

# 28. ServicesGridBlock

Существует отдельный:

``` text
services_grid
```

Он выводит каталог услуг.

Schema:

``` text
block_name
title
subtitle
show_description
```

Provider тот же:

``` text
ServicesProvider
```

Blade:

``` text
resources/views/blocks/services_grid.blade.php
```

CSS:

``` text
resources/css/blocks/services-grid.css
```

уже реализован и содержит полноценную сетку карточек.

Это означает, что `ServicesGridBlock` фактически является уже
стилизованным вариантом каталога услуг.

------------------------------------------------------------------------

# 29. ServicesBlock и ServicesGridBlock --- важное архитектурное различие

Сейчас существуют два блока:

``` text
services
services_grid
```

Оба получают данные через:

``` text
ServicesProvider
```

Но frontend отличается.

`services_grid`:

-   полноценная сетка;
-   изображения;
-   карточки;
-   ссылки на `/uslugi/{slug}`;
-   описание;
-   анимация.

`services`:

-   минимальная разметка;
-   CSS отсутствует.

Поэтому перед дальнейшей разработкой нельзя автоматически считать эти
блоки дублями.

Правильный вопрос:

``` text
services
```

должен быть ли:

-   компактным блоком услуг;
-   горизонтальным списком;
-   featured-секцией;
-   другим визуальным представлением?

А:

``` text
services_grid
```

оставить каталогом.

------------------------------------------------------------------------

# 30. BuilderRenderer

Главный runtime Builder:

``` text
app/Platform/Builder/Renderers/BuilderRenderer.php
```

Получает:

``` php
render(array $blocks, mixed $model = null)
```

Каждый блок имеет структуру:

``` text
type
data
```

Renderer преобразует его в:

``` text
type
viewData
```

Пример:

``` text
services
   ↓
ServicesProvider::make()
   ↓
{
    block,
    services
}
```

------------------------------------------------------------------------

# 31. Provider Layer

Providers отделяют получение данных от Blade.

Существуют:

``` text
BaseProvider
FooterProvider
GalleryProvider
HeaderProvider
HeroProvider
InnerHeroProvider
MenuProvider
ProjectsProvider
ServicesProvider
SiteSettingsProvider
StatsProvider
```

Это один из наиболее важных архитектурных слоёв проекта.

Blade не должен самостоятельно выполнять сложные Eloquent-запросы.

------------------------------------------------------------------------

# 32. BuilderRenderer --- текущая особенность

Для:

``` text
hero
services
services_grid
projects
gallery
stats
footer
inner_hero
```

есть специализированные Providers.

Но:

``` text
features
comparison
cta
```

не имеют отдельных Providers в `BuilderRenderer`.

Для них используется:

``` php
default => [
    'block' => $block['data'],
]
```

Это работает потому, что их Blade может работать непосредственно с
данными блока.

Это допустимо для простых content-only блоков.

------------------------------------------------------------------------

# 33. Публичный Page flow

Route:

``` text
/{slug?}
```

ведёт:

``` text
PageController
```

Алгоритм:

``` text
slug
 ↓
Page
 ↓
is_published
 ↓
Page::blocks
 ↓
BuilderRenderer
 ↓
pages.page
```

Blade:

``` text
resources/views/pages/page.blade.php
```

проходит по:

``` text
$blocks
```

и подключает:

``` text
blocks.{type}
```

------------------------------------------------------------------------

# 34. Service page flow

Route:

``` text
/uslugi/{service:slug}
```

ведёт:

``` text
ServiceController
```

Алгоритм:

``` text
slug
 ↓
Service
 ↓
is_published
 ↓
Service::blocks
 ↓
BuilderRenderer($blocks, $service)
 ↓
builder.service
```

Это важная архитектурная возможность.

Передача:

``` php
$model = $service
```

позволяет Provider узнать текущую сущность.

Именно поэтому:

``` text
ProjectsBlock
```

может работать в режиме:

``` text
current_service
```

------------------------------------------------------------------------

# 35. Project page

Route:

``` text
/raboty/{project:slug}
```

ведёт:

``` text
ProjectController
```

Сейчас проект отображается отдельным:

``` text
resources/views/projects/show.blade.php
```

и не использует BuilderRenderer.

Таким образом:

``` text
Page → Builder
Service → Builder
Project → собственный Blade
```

Это сознательно/фактически разные пути.

------------------------------------------------------------------------

# 36. Lead system

Публичная точка:

``` text
POST /lead
```

Controller:

``` text
LeadController
```

Валидация выполняется прямо в `store()` через `$request->validate()`.

Отдельно существует:

``` text
app/Http/Requests/StoreLeadRequest.php
```

но `LeadController` его в текущем коде не использует.

Это архитектурное несоответствие, которое следует устранить позднее.

Lead сохраняет:

``` text
name
phone
object_type
source
status
processed
```

------------------------------------------------------------------------

# 37. Lead statuses

Есть:

``` text
app/Enums/LeadStatus.php
```

Но текущий `LeadForm` использует строковые значения непосредственно:

``` text
new
work
clarification
payment
done
```

То есть Enum существует, но не является источником истины для формы и
модели.

Это потенциальная точка рефакторинга.

------------------------------------------------------------------------

# 38. Media architecture

Есть несколько подходов.

## Image model

``` text
Image
└── Media Library collection: image
```

Используется глобальными настройками и Builder.

## Project

``` text
cover
gallery
```

через Media Library.

## Gallery

``` text
gallery
```

через Media Library.

## Builder uploads

Некоторые блоки используют:

``` text
FileUpload
```

напрямую:

``` text
Features
Comparison
CTA
```

Это означает, что media architecture пока неоднородна.

Целевая архитектура должна решить:

``` text
Image model / Media Library
```

или:

``` text
direct FileUpload
```

как основной подход для каждого класса контента.

------------------------------------------------------------------------

# 39. Site Settings

`SiteSettingResource` реализует singleton-like поведение:

``` php
canCreate(): bool
{
    return static::getModel()::count() === 0;
}
```

То есть создавать можно только первую запись.

После её создания пользователь должен редактировать существующую.

Provider:

``` text
SiteSettingsProvider
```

берёт:

``` text
SiteSetting::first()
```

и получает:

``` text
logo
favicon
settings
```

Это используется прежде всего Hero.

------------------------------------------------------------------------

# 40. Menu architecture

MenuItem привязывается к Page:

``` text
MenuItem
   └── page_id
```

или использует прямой URL:

``` text
url
```

`resolved_url` отдаёт:

``` text
Page home → /
Page slug → /slug
нет Page → url
нет ничего → #
```

`MenuProvider` возвращает:

``` text
title
url
```

и используется Hero.

Это означает, что меню уже автоматически синхронизируется с Page routing
через `page_id`.

------------------------------------------------------------------------

# 41. Frontend architecture

Публичный layout:

``` text
resources/views/layouts/app.blade.php
```

подключает:

``` text
resources/css/app.css
resources/js/app.js
```

и глобально:

``` text
<x-lead-modal />
```

------------------------------------------------------------------------

# 42. CSS architecture

Публичный CSS разделён по блокам:

``` text
resources/css/blocks/
├── hero.css
├── hero-mobile.css
├── inner-hero.css
├── services.css
├── services-grid.css
├── projects.css
├── gallery.css
├── features.css
├── comparison.css
├── stats.css
├── cta.css
└── footer.css
```

Это соответствует Builder-подходу:

``` text
Block
 ├── Blade
 ├── CSS
 └── JS
```

Однако `services.css` сейчас пуст, что прямо связано с текущей задачей.

------------------------------------------------------------------------

# 43. JavaScript architecture

Основной entrypoint:

``` text
resources/js/app.js
```

Подключает:

``` text
comparison
stats
cta
modal
footer
hero
services-grid
```

Swiper используется для проектов.

Анимации реализованы через:

``` text
IntersectionObserver
```

Builder-блоки с JS:

``` text
Hero
Stats
CTA
Comparison
Footer
Projects
ServicesGrid
```

------------------------------------------------------------------------

# 44. Tailwind

Tailwind 4 установлен:

``` text
tailwindcss
@tailwindcss/vite
```

Но публичный frontend преимущественно построен на обычном CSS.

Это архитектурно соответствует текущему решению команды:

> Tailwind не является обязательным инструментом для публичных блоков.

Для публичного сайта рекомендуется продолжать существующий
CSS-компонентный подход, а не смешивать его без необходимости с
Tailwind.

Filament при этом продолжает использовать собственную
Tailwind-инфраструктуру.

------------------------------------------------------------------------

# 45. Filament Dashboard CSS

Dashboard имеет отдельный:

``` text
resources/css/components/stats-blade.css
```

и подключается только в Filament Panel через Render Hook.

Это правильное разделение:

``` text
Public CSS
    ≠
Admin CSS
```

------------------------------------------------------------------------

# 46. Database

Основные таблицы:

``` text
users
pages
services
projects
project_service
leads
post_categories
posts
faqs
testimonials
media
images
galleries
hero_sections
comparison_sections
menu_items
site_settings
```

Также Laravel infrastructure:

``` text
cache
cache_locks
jobs
job_batches
failed_jobs
sessions
password_reset_tokens
```

------------------------------------------------------------------------

# 47. PostgreSQL-specific point

Миграция:

``` text
2026_07_12_194928_change_services_blocks_to_jsonb.php
```

использует:

``` sql
ALTER TABLE services
ALTER COLUMN blocks
TYPE jsonb
```

Следовательно, текущая архитектура `Service.blocks` предполагает
PostgreSQL.

Старый `DEVELOPMENT_GUIDE.md`, где указан MySQL, требует обновления.

------------------------------------------------------------------------

# 48. Migration inconsistency

Есть:

``` text
2026_07_01_203837_add_blocks_to_services_table.php
```

который создаёт:

``` text
services.blocks
```

а затем:

``` text
2026_07_12_194928_change_services_blocks_to_jsonb.php
```

меняет его тип.

Это нормально как историческая цепочка миграций.

Однако если миграции были применены частично/вручную или база уже
содержит `blocks`, повторное создание колонки приведёт к:

``` text
Duplicate column "blocks"
```

Поэтому миграции нельзя переписывать задним числом в уже существующей
development database без проверки `migrations`.

------------------------------------------------------------------------

# 49. Legacy / transitional entities

В проекте есть сущности:

``` text
HeroSection
ComparisonSection
```

которые дублируют часть концепции Builder.

Одновременно:

``` text
HeroBlock
ComparisonBlock
```

уже хранят контент непосредственно в JSON Builder.

Это соответствует старой идее из README:

> сначала Builder, затем нормализация повторяемых секций в отдельные
> сущности.

На текущей контрольной точке эту нормализацию **не следует начинать без
необходимости**.

------------------------------------------------------------------------

# 50. Архитектурный принцип проекта

Из README следует важный принцип:

## Этап 1

Сначала:

``` text
полностью рабочий frontend
```

и:

``` text
Builder
```

без преждевременной нормализации.

## Этап 2

После завершения frontend:

``` text
нормализация CMS
```

с отдельными сущностями:

``` text
HeroSection
StatsSection
CTASection
FAQSection
TeamSection
GallerySection
```

и т.д.

Следовательно, текущую архитектуру Builder не нужно преждевременно
усложнять.

------------------------------------------------------------------------

# 51. Что уже является рабочим ядром

Наиболее зрелые части:

``` text
Page
Service
Project
Lead
SiteSetting
Image
Gallery
MenuItem
```

и:

``` text
BuilderRegistry
BuilderRenderer
Providers
DashboardRegistry
DashboardService
```

Это следует считать текущим ядром системы.

------------------------------------------------------------------------

# 52. Что сейчас является незавершённым

## Builder

-   Services block presentation;
-   часть блоков не имеет Provider;
-   Media handling неоднороден;
-   Builder Registry пока статический;
-   отсутствуют DTO/Exception/Support уровни несмотря на наличие
    каталогов.

## CMS

-   Blog не реализован;
-   FAQ не реализован;
-   Testimonials не реализованы;
-   SEO не реализован как отдельный CMS module;
-   Redirects отсутствуют;
-   Analytics отсутствует;
-   Local pages отсутствуют;
-   Sources отсутствуют.

## CRM

-   Lead работает;
-   полноценная CRM ещё не реализована;
-   нет истории статусов;
-   нет ответственного менеджера;
-   нет уведомлений;
-   нет экспорта.

------------------------------------------------------------------------

# 53. Что нельзя делать без необходимости

На текущем этапе не следует:

1.  Создавать второй Builder.
2.  Создавать второй `ServicesProvider`.
3.  Создавать третью модель для услуг.
4.  Создавать отдельную таблицу для каждого Builder-блока.
5.  Переписывать рабочий `BuilderRenderer` ради одного блока.
6.  Переносить публичный frontend целиком на Tailwind.
7.  Переписывать Filament.
8.  Нормализовать все JSON-блоки в отдельные таблицы до завершения
    frontend.
9.  Удалять старые сущности только потому, что они выглядят
    дублирующими, без проверки использования.

------------------------------------------------------------------------

# 54. Главная ближайшая задача: ServicesBlock

На момент контрольной точки состояние:

``` text
ServicesBlock
    ✅ зарегистрирован
    ✅ имеет Filament schema
    ✅ имеет режим all
    ✅ имеет режим featured
    ✅ имеет режим manual
    ✅ имеет limit
    ✅ имеет title
    ✅ имеет subtitle
    ✅ имеет button
    ✅ имеет ServicesProvider
    ✅ получает опубликованные Service
    ❌ не имеет законченной frontend-разметки
    ❌ не имеет законченного CSS
```

Следовательно, **новая бизнес-логика для выбора услуг не требуется**.

Нужно закончить:

``` text
resources/views/blocks/services.blade.php
resources/css/blocks/services.css
```

и при необходимости:

``` text
resources/js/services.js
```

------------------------------------------------------------------------

# 55. Рекомендуемая реализация ServicesBlock

Текущая цепочка должна остаться:

``` text
ServiceResource
    ↓
ServiceForm
    ↓
BuilderRegistry
    ↓
ServicesBlock
    ↓
services JSON
    ↓
BuilderRenderer
    ↓
ServicesProvider
    ↓
services.blade.php
    ↓
services.css
```

Не следует создавать:

``` text
ServiceBlockService
ServicePageBuilder
ServiceRenderer
```

если для них нет реальной архитектурной необходимости.

------------------------------------------------------------------------

# 56. ServicesBlock и текущий ServicesGrid

Текущее состояние:

``` text
services
```

можно использовать как отдельную секцию:

``` text
"Наши услуги"
```

с более компактным визуальным представлением.

``` text
services_grid
```

остаётся:

``` text
"Каталог услуг"
```

с карточками.

Это позволяет иметь два визуальных представления одних данных без
дублирования backend-логики.

------------------------------------------------------------------------

# 57. Рекомендуемый контракт Builder Provider

Для простого data-driven блока:

``` php
return [
    'block' => $block,
    'services' => $services,
];
```

Blade получает:

``` text
$block
$services
```

и не должен самостоятельно обращаться к:

``` text
Service::query()
```

------------------------------------------------------------------------

# 58. Рекомендуемый контракт Builder Blade

Каждый Builder Blade должен:

1.  иметь собственный корневой элемент;
2.  иметь собственный CSS-класс;
3.  работать с переданными Provider данными;
4.  не выполнять сложную бизнес-логику;
5.  не выполнять Eloquent queries;
6.  не знать о Filament.

Например:

``` text
<section class="services">
```

а не:

``` text
<section>
```

Это особенно важно для `ServicesBlock`, потому что сейчас его Blade не
имеет собственного CSS API.

------------------------------------------------------------------------

# 59. Публичный URL-контракт

Страницы:

``` text
/{slug}
```

Главная:

``` text
/
```

Услуга:

``` text
/uslugi/{service:slug}
```

Проект:

``` text
/raboty/{project:slug}
```

Lead endpoint:

``` text
POST /lead
```

Эти URL являются частью текущего frontend-контракта и не должны
изменяться без необходимости.

------------------------------------------------------------------------

# 60. Architectural boundaries

## CMS

Отвечает за:

``` text
Models
Resources
Dashboard
Builder configuration
```

## Platform Builder

Отвечает за:

``` text
Block definitions
Provider data
Rendering
Registry
```

## Http

Отвечает за:

``` text
HTTP input
validation
controllers
```

## Resources

Отвечают за:

``` text
frontend representation
```

## CSS/JS

Отвечают за:

``` text
presentation
interaction
animation
```

------------------------------------------------------------------------

# 61. Current dependency direction

Основное направление:

``` text
Filament Resource
       ↓
Builder Registry
       ↓
Block schema
       ↓
stored JSON
       ↓
Controller
       ↓
BuilderRenderer
       ↓
Provider
       ↓
Blade
       ↓
CSS/JS
```

Обратная зависимость:

``` text
Blade → Filament
```

нежелательна.

Именно поэтому публичные Blade-шаблоны не должны использовать Filament
Resource API.

Dashboard --- исключение, поскольку он является частью Admin.

------------------------------------------------------------------------

# 62. Архитектурные проблемы, зафиксированные без немедленного исправления

## 62.1 Interface не используется AbstractWidget

Есть:

``` text
DashboardWidget
```

но:

``` text
AbstractWidget
```

не реализует:

``` text
DashboardWidget
```

Целевой вариант:

``` php
abstract class AbstractWidget implements DashboardWidget
```

------------------------------------------------------------------------

## 62.2 DashboardService сам регистрирует всё

Сейчас:

``` text
DashboardService::boot()
```

знает конкретные Widgets.

Это рабочий вариант.

Позже регистрацию можно вынести в Service Provider/config, если
количество виджетов станет большим.

Сейчас это преждевременно.

------------------------------------------------------------------------

## 62.3 ServicesProvider и ProjectsProvider частично дублируют BaseProvider

`BaseProvider` уже содержит:

``` text
published()
featured()
limit()
manual()
```

но `ServicesProvider` и `ProjectsProvider` частично повторяют эту логику
вручную.

Это потенциальный рефакторинг, но не обязательный для текущей задачи.

------------------------------------------------------------------------

## 62.4 HeaderProvider и MenuProvider

Оба работают с MenuItem.

При этом:

``` text
HeaderProvider
```

сам строит URL через relation `page`.

А:

``` text
MenuProvider
```

использует:

``` text
resolved_url
```

Лучше в будущем оставить один источник истины ---
`MenuItem::resolved_url`.

------------------------------------------------------------------------

## 62.5 Service schema

`ServiceForm` уже использует:

``` text
BuilderRegistry
```

поэтому отдельная "страница услуги" и Builder страницы услуги фактически
уже существуют.

Не нужно создавать ещё одну систему описания услуги.

------------------------------------------------------------------------

## 62.6 Project не использует Builder

Project имеет отдельный Blade.

Это не ошибка само по себе, но важно помнить, что Builder сейчас
применяется:

``` text
Page
Service
```

а не:

``` text
Project
```

------------------------------------------------------------------------

# 63. Документация проекта

Есть:

``` text
README.md
docs/DEVELOPMENT_GUIDE.md
```

Но текущая документация отстаёт от фактического кода.

В частности:

-   указан MySQL, хотя миграции используют PostgreSQL `jsonb`;
-   упомянуты сущности, которых пока нет;
-   часть новых CMS-разделов отсутствует;
-   ServicesGrid и Dashboard не отражены;
-   текущая архитектура Builder Providers не описана подробно.

Этот документ является новой контрольной точкой и должен использоваться
вместе с существующей документацией до тех пор, пока старые документы не
будут синхронизированы.

------------------------------------------------------------------------

# 64. Контрольная точка для дальнейшей разработки

Считать текущую архитектуру следующей базовой версией:

``` text
DESIRE HOUSE CMS
│
├── Laravel 13
│
├── Filament 5
│   ├── AdminPanelProvider
│   ├── Resources
│   └── Dashboard
│
├── CMS
│   └── Dashboard
│       ├── Registry
│       ├── Service
│       └── Widgets
│
├── Platform
│   └── Builder
│       ├── Blocks
│       ├── Providers
│       ├── Registry
│       └── Renderer
│
├── Models
│   ├── Page
│   ├── Service
│   ├── Project
│   ├── Lead
│   ├── Image
│   ├── Gallery
│   ├── MenuItem
│   └── SiteSetting
│
└── Frontend
    ├── Blade
    ├── CSS
    └── JavaScript
```

------------------------------------------------------------------------

# 65. Следующий этап разработки

Непосредственно после этой контрольной точки:

## Этап 1 --- ServicesBlock

Не менять backend без необходимости.

Сделать:

``` text
resources/views/blocks/services.blade.php
resources/css/blocks/services.css
```

и проверить:

``` text
mode=all
mode=featured
mode=manual
limit
title
subtitle
button
```

## Этап 2 --- service page

Проверить, что:

``` text
/uslugi/{slug}
```

использует:

``` text
Service.blocks
```

и все зарегистрированные Builder-блоки корректно работают внутри
Service.

Особенно:

``` text
inner_hero
services
projects(current_service)
gallery
cta
```

## Этап 3 --- привести service presentation к единому UI

Карточки услуги должны использовать существующую frontend-систему и CSS,
а не отдельный Tailwind-механизм.

------------------------------------------------------------------------

# 66. Правило дальнейшей разработки

Перед созданием нового класса/сервиса/провайдера необходимо сначала
проверить:

``` text
Есть ли уже существующий механизм?
```

Для текущего проекта это особенно важно.

Например, перед созданием нового механизма услуг проверяем:

``` text
ServicesBlock
ServicesProvider
Service
BuilderRenderer
```

Если они уже решают задачу, расширяем их вместо создания параллельной
архитектуры.

------------------------------------------------------------------------

# 67. Финальная схема Services на контрольной точке

``` text
                ┌──────────────────────┐
                │   ServiceResource    │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │     ServiceForm      │
                │                      │
                │ Builder::make(blocks)│
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │   BuilderRegistry    │
                │                      │
                │   ServicesBlock      │
                └──────────┬───────────┘
                           │
                           ▼
                    services JSON
                           │
                           ▼
                ┌──────────────────────┐
                │   ServiceController  │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │   BuilderRenderer    │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │   ServicesProvider   │
                │                      │
                │ all                  │
                │ featured             │
                │ manual               │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │ services.blade.php   │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │      services.css    │
                └──────────────────────┘
```

**Именно эту цепочку считаем канонической для ServicesBlock.**

------------------------------------------------------------------------

# 68. Итог

На текущем этапе проект уже имеет полноценный архитектурный каркас CMS:

-   Filament используется как административная платформа;
-   публичный frontend отделён от Filament;
-   Page и Service используют JSON Builder;
-   Builder имеет Registry;
-   данные блоков отделены от представления через Providers;
-   Dashboard вынесен в отдельный CMS-модуль;
-   Lead уже является общей точкой обработки заявок;
-   SiteSettings и MenuItem используются как глобальные источники
    данных;
-   Media Library интегрирован;
-   CSS организован по блокам.

Главная ближайшая задача не требует новой архитектуры.

Она заключается в завершении уже существующего:

``` text
ServicesBlock
```

через:

``` text
Blade + CSS
```

с сохранением:

``` text
ServicesProvider
BuilderRenderer
BuilderRegistry
Service model
```

как текущего источника истины.

**Этот документ следует считать контрольной точкой архитектуры
проекта.**

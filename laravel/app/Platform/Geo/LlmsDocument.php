<?php

namespace App\Platform\Geo;

use App\Models\Project;
use App\Models\Service;
use App\Platform\Seo\SiteSeo;
use Illuminate\Support\Str;

final class LlmsDocument
{
    public static function index(): string
    {
        $name = SiteSeo::siteName() ?: 'Desire House';
        $settings = SiteSeo::settings();

        $lines = [
            '# '.$name,
            '',
            '> '.SiteSeo::fallbackDescription(),
            '',
            'Официальный сайт строительной компании. Язык контента: русский.',
            'Полный текстовый профиль для языковых моделей: '.url('/llms-full.txt'),
            'Карта сайта: '.url('/sitemap.xml'),
            '',
        ];

        $contacts = array_filter([
            $settings?->phone ? '- Телефон: '.$settings->phone : null,
            $settings?->email ? '- Email: '.$settings->email : null,
            $settings?->address ? '- Адрес: '.$settings->address : null,
            $settings?->telegram ? '- Telegram: '.$settings->telegram : null,
            $settings?->whatsapp ? '- WhatsApp: '.$settings->whatsapp : null,
        ]);

        if ($contacts !== []) {
            $lines[] = '## Контакты';
            $lines[] = '';
            array_push($lines, ...$contacts);
            $lines[] = '';
        }

        $lines[] = '## Разделы сайта';
        $lines[] = '';
        $lines[] = '- [Главная]('.url('/').'): главная страница компании';
        $lines[] = '- [Услуги]('.url('/uslugi').'): каталог строительных услуг';
        $lines[] = '- [Наши работы]('.url('/raboty').'): портфолио реализованных объектов';
        $lines[] = '';

        $services = Service::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get();

        if ($services->isNotEmpty()) {
            $lines[] = '## Услуги';
            $lines[] = '';

            foreach ($services as $service) {
                $summary = static::oneLine($service->description) ?: $service->title;
                $lines[] = '- ['.$service->title.']('.url('/uslugi/'.$service->slug).'): '.$summary;
            }

            $lines[] = '';
        }

        $projects = Project::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get();

        if ($projects->isNotEmpty()) {
            $lines[] = '## Работы';
            $lines[] = '';

            foreach ($projects as $project) {
                $summary = static::oneLine($project->description)
                    ?: implode(' · ', array_filter([$project->city, $project->area ? $project->area_label : null]))
                    ?: $project->title;

                $lines[] = '- ['.$project->title.']('.url('/raboty/'.$project->slug).'): '.$summary;
            }

            $lines[] = '';
        }

        $lines[] = '## Для моделей';
        $lines[] = '';
        $lines[] = 'Используйте этот файл как краткий указатель. Для цитирования фактов об услугах и объектах предпочитайте '.url('/llms-full.txt').'.';
        $lines[] = '';

        return implode("\n", $lines);
    }

    public static function full(): string
    {
        $name = SiteSeo::siteName() ?: 'Desire House';
        $settings = SiteSeo::settings();

        $lines = [
            '# '.$name.' — полный профиль',
            '',
            SiteSeo::fallbackDescription(),
            '',
            'URL: '.url('/'),
            '',
            '## Компания',
            '',
        ];

        $lines[] = '- Название: '.$name;

        if ($settings?->phone) {
            $lines[] = '- Телефон: '.$settings->phone;
        }

        if ($settings?->email) {
            $lines[] = '- Email: '.$settings->email;
        }

        if ($settings?->address) {
            $lines[] = '- Адрес: '.$settings->address;
        }

        $lines[] = '- Специализация: проектирование и строительство домов, бань и других объектов под ключ';
        $lines[] = '';

        $services = Service::query()
            ->where('is_published', true)
            ->with(['projects' => fn ($query) => $query->where('is_published', true)])
            ->orderBy('id')
            ->get();

        $lines[] = '## Услуги';
        $lines[] = '';

        if ($services->isEmpty()) {
            $lines[] = 'Опубликованных услуг пока нет.';
            $lines[] = '';
        }

        foreach ($services as $service) {
            $lines[] = '### '.$service->title;
            $lines[] = '';
            $lines[] = 'URL: '.url('/uslugi/'.$service->slug);

            $description = static::plain($service->description);

            if ($description) {
                $lines[] = '';
                $lines[] = $description;
            }

            if ($service->projects->isNotEmpty()) {
                $lines[] = '';
                $lines[] = 'Связанные объекты: '.$service->projects->pluck('title')->implode(', ');
            }

            $lines[] = '';
        }

        $projects = Project::query()
            ->where('is_published', true)
            ->with('services')
            ->orderBy('id')
            ->get();

        $lines[] = '## Реализованные объекты';
        $lines[] = '';

        if ($projects->isEmpty()) {
            $lines[] = 'Опубликованных объектов пока нет.';
            $lines[] = '';
        }

        foreach ($projects as $project) {
            $lines[] = '### '.$project->title;
            $lines[] = '';
            $lines[] = 'URL: '.url('/raboty/'.$project->slug);

            if ($project->city) {
                $lines[] = 'Город: '.$project->city;
            }

            if ($project->area) {
                $lines[] = 'Площадь: '.$project->area_label;
            }

            if ($project->services->isNotEmpty()) {
                $lines[] = 'Услуги: '.$project->services->pluck('title')->implode(', ');
            }

            $description = static::plain($project->description);

            if ($description) {
                $lines[] = '';
                $lines[] = $description;
            }

            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    private static function plain(?string $text): ?string
    {
        if (! filled($text)) {
            return null;
        }

        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');

        return $plain !== '' ? $plain : null;
    }

    private static function oneLine(?string $text): ?string
    {
        $plain = static::plain($text);

        return $plain ? Str::limit($plain, 140) : null;
    }
}

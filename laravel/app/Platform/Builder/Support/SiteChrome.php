<?php

namespace App\Platform\Builder\Support;

use App\Platform\Builder\Providers\MenuProvider;
use App\Platform\Builder\Providers\SiteSettingsProvider;

final class SiteChrome
{
    public static function objectTypes(): array
    {
        return [
            'Дом',
            'Фундамент',
            'Баня',
            'Гараж',
            'Пристройка',
            'Терраса',
            'Другое',
        ];
    }

    public static function cta(string $subtitle, string $source, ?string $description = null): array
    {
        return [
            'type' => 'cta',
            'data' => [
                'title' => 'Рассчитать стоимость',
                'subtitle' => $subtitle,
                'description' => $description
                    ?: 'Оставьте заявку — подготовим предложение под ваш объект и свяжемся в ближайшее время.',
                'button_text' => 'Получить расчёт',
                'source' => $source,
                'object_types' => self::objectTypes(),
            ],
        ];
    }

    public static function footer(): array
    {
        $site = SiteSettingsProvider::make();
        $settings = $site['settings'] ?? null;

        return [
            'type' => 'footer',
            'data' => [
                'company' => $settings?->company_name,
                'description' => $settings?->address,
                'phone' => $settings?->phone,
                'email' => $settings?->email,
                'telegram' => $settings?->telegram,
                'whatsapp' => $settings?->whatsapp,
                'copyright' => '© '.date('Y').' '.($settings?->company_name ?: ''),
                'menu' => MenuProvider::make(),
                'logo_id' => $settings?->logo_image_id,
            ],
        ];
    }
}

<?php

namespace App\CMS\Dashboard\Widgets;

use App\Filament\Admin\Resources\Leads\LeadResource;
use App\Models\Lead;

class RecentLeadsWidget extends AbstractWidget
{
    public static function key(): string
    {
        return 'recent-leads';
    }

    public static function title(): string
    {
        return 'Последние заявки';
    }

    public static function span(): string
    {
        return 'main';
    }

    public function data(): array
    {
        return Lead::query()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (Lead $lead) {
                $initials = collect(preg_split('/\s+/u', trim((string) $lead->name)))
                    ->filter()
                    ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
                    ->take(2)
                    ->implode('');

                return [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'phone' => $lead->phone,
                    'object_type' => $lead->object_type,
                    'created_at' => $lead->created_at,
                    'initials' => $initials !== '' ? $initials : '?',
                    'href' => LeadResource::getUrl('edit', ['record' => $lead]),
                ];
            })
            ->toArray();
    }
}
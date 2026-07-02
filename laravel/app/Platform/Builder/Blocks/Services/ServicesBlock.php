<?php

namespace App\Platform\Builder\Blocks\Services;

use App\Platform\Builder\Contracts\BuilderBlock;
use App\Platform\Builder\Core\BlockDefinition;
use App\Platform\Builder\Enums\BlockCategory;

final class ServicesBlock implements BuilderBlock
{
    public static function definition(): BlockDefinition
    {
        return new BlockDefinition(
            name: 'services',
            title: 'Услуги',
            description: 'Вывод списка услуг',
            icon: 'heroicon-o-wrench-screwdriver',
            category: BlockCategory::SERVICES,
            supportsMedia: false,
        );
    }

    public static function schema(): \Filament\Forms\Components\Builder\Block
    {
        return ServicesSchema::make();
    }
}

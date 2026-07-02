<?php

namespace App\Platform\Builder\Contracts;

use App\Platform\Builder\Core\BlockDefinition;
use Filament\Forms\Components\Builder\Block;

interface BuilderBlock
{
    /**
     * Паспорт блока.
     */
    public static function definition(): BlockDefinition;

    /**
     * Схема Filament Builder.
     */
    public static function schema(): Block;
}

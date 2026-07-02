<?php

namespace App\Platform\Builder\Core;

use App\Platform\Builder\Enums\BlockCategory;

final readonly class BlockDefinition
{
    public function __construct(
        public string $name,
        public string $title,
        public string $description,
        public string $icon,
        public BlockCategory $category,
        public string $version = '1.0.0',
        public bool $supportsSeo = true,
        public bool $supportsGeo = true,
        public bool $supportsMedia = false,
    ) {}
}

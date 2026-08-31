<?php

namespace App\Platform\Builder\Registry;

use App\Platform\Builder\Blocks\ComparisonBlock;
use App\Platform\Builder\Blocks\CtaBlock;
use App\Platform\Builder\Blocks\FeaturesBlock;
use App\Platform\Builder\Blocks\FooterBlock;
use App\Platform\Builder\Blocks\GalleryBlock;
use App\Platform\Builder\Blocks\HeroBlock;
use App\Platform\Builder\Blocks\ProjectsBlock;
use App\Platform\Builder\Blocks\ServicesBlock;
use App\Platform\Builder\Blocks\StatsBlock;
use App\Platform\Builder\Blocks\InnerHeroBlock;
use App\Platform\Builder\Blocks\ServiceAboutBlock;
use App\Platform\Builder\Blocks\ServicesGridBlock;

final class BuilderRegistry
{
    public static function blocks(): array
    {
        return [
            HeroBlock::make(),
            InnerHeroBlock::make(),
            ServiceAboutBlock::make(),
            FeaturesBlock::make(),
            StatsBlock::make(),
            ServicesBlock::make(),
            GalleryBlock::make(),
            ProjectsBlock::make(),
            ComparisonBlock::make(),
            CtaBlock::make(),
            FooterBlock::make(),
            ServicesGridBlock::make(),
        ];
    }
}

<?php

namespace App\Platform\Builder\Enums;

enum BlockCategory: string
{
    case BASIC = 'basic';

    case CONTENT = 'content';

    case COMPANY = 'company';

    case SERVICES = 'services';

    case PROJECTS = 'projects';

    case CONTACTS = 'contacts';

    case FORMS = 'forms';

    case MEDIA = 'media';

    case MARKETING = 'marketing';

    case SEO = 'seo';
}

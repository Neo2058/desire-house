@props([
    'logo' => null,
    'settings' => null,
    'menu' => [],
    'phone' => null,
    'telegram' => null,
    'whatsapp' => null,
])

<header class="hero__header">

    <div class="container hero__header-container">

        <a href="/" class="hero__logo">

            @if($logo)

                <img
                    src="{{ $logo }}"
                    alt="{{ $settings?->company_name }}"
                >

            @endif

        </a>

        <nav class="hero__menu">

            @foreach($menu as $item)

                <a
                    href="{{ $item['url'] }}"
                    @class(['is-active' => url()->current() === url($item['url'])])
                >

                    {{ $item['title'] }}

                </a>

            @endforeach

        </nav>

        <button
            class="hero__burger"
            id="hero-burger"
            type="button"
            aria-label="Меню"
        >

            <span></span>
            <span></span>
            <span></span>

        </button>

        <div class="hero__contacts">
            @if($phone)

                <a
                    href="tel:{{ preg_replace('/\D/', '', $phone) }}"
                    class="hero__phone"
                >
                    {{ $phone }}
                </a>

            @endif
                <div class="hero__messengers">

                    @if($telegram)

                        <a
                            href="{{ $telegram }}"
                            target="_blank"
                        >
                            Telegram
                        </a>

                    @endif

                    @if($whatsapp)

                        <a
                            href="{{ $whatsapp }}"
                            target="_blank"
                        >
                            WhatsApp
                        </a>

                    @endif

                </div>
        </div>

    </div>

</header>

<div
    class="mobile-menu"
    id="mobile-menu"
>

    <div class="mobile-menu__overlay"></div>

    <div class="mobile-menu__panel">

        <button
            class="mobile-menu__close"
            type="button"
        >

            ×

        </button>

        <nav class="mobile-menu__nav">

            @foreach($menu as $item)

                <a
                    href="{{ $item['url'] }}"
                    @class([
                        'mobile-menu__link',
                        'is-active' => url()->current() === url($item['url']),
                    ])
                >

                    {{ $item['title'] }}

                </a>

            @endforeach

        </nav>

        <div class="mobile-menu__contacts">

            @if($phone)

                <a
                    href="tel:{{ preg_replace('/\D/', '', $phone) }}"
                    class="hero__phone"
                >
                    {{ $phone }}
                </a>

            @endif

            <div class="mobile-menu__messengers">

                @if($telegram)

                    <a
                        href="{{ $telegram }}"
                        target="_blank"
                    >
                        Telegram
                    </a>

                @endif

                @if($whatsapp)

                    <a
                        href="{{ $whatsapp }}"
                        target="_blank"
                    >
                        WhatsApp
                    </a>

                @endif

            </div>

        </div>

    </div>

</div>

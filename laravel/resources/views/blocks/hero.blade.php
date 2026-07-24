<section class="hero"
         @if($background)
             style="background-image:url('{{ $background }}')"
          @endif
>

    <div class="hero__overlay"></div>

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

                    <a href="{{ $item['url'] }}">

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
                        class="mobile-menu__link"
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

                    Telegram

                    WhatsApp

                </div>

            </div>

        </div>

    </div>

    <div class="container hero__content">

        <div class="hero__left">

            @if(!empty($block['subtitle']))
                <div class="hero__subtitle">

                    {{ $block['subtitle'] }}

                </div>
            @endif

            <h1 class="hero__title">

                {{ $block['title'] }}

            </h1>

            @if(!empty($block['description']))
                <div class="hero__description">

                    {{ $block['description'] }}

                </div>
            @endif

            @if(!empty($block['button_text']))
                    <a
                        href="#"
                        class="hero__button"
                        data-open-lead
                    >
                        {{ $block['button_text'] }}
                    </a>
            @endif

        </div>

        <div class="hero__right">

            @if($person)

                <img
                    src="{{ $person }}"
                    alt=""
                >

            @endif

        </div>

    </div>

</section>

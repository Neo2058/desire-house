<section class="hero"
         @if($background)
             style="background-image:url('{{ $background }}')"
          @endif
>

    <div class="hero__overlay"></div>

    <header class="hero__header">

        <div class="container hero__header-container">

            <a href="/" class="hero__logo">

                <img src="/images/logo.png" alt="Desire House">

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
                <a href="tel:+79309743240" class="hero__phone">
                    <span>+7</span><span> </span><span>(930)</span><span> </span><span>974-32-40</span>
                </a>
                <div class="hero__messengers">
                    Telegram / WhatsApp
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

                <a
                    href="tel:+79309743240"
                    class="mobile-menu__phone"
                >

                    +7 (930) 974-32-40

                </a>

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

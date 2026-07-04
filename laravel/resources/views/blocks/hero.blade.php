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

                <a href="#">Услуги</a>
                <a href="#">Наши работы</a>
                <a href="#">О нас</a>
                <a href="#">Этапы работ</a>
                <a href="#">Контакты</a>

            </nav>

            <div class="hero__contacts">
                <a href="tel:+79999999999" class="hero__phone">
                    <span>+7</span><span> </span><span>(999)</span><span> </span><span>999-99-99</span>
                </a>
                <div class="hero__messengers">
                    Telegram / WhatsApp
                </div>
            </div>

        </div>

    </header>

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
                    href="{{ $block['button_url'] }}"
                    class="hero__button"
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

<footer class="footer">

    <div class="container">

        <div class="footer__grid">

            <div class="footer__about">

                @if($logo)

                    <img
                        class="footer__logo"
                        src="{{ $logo }}"
                        alt="{{ $block['company'] }}"
                    >

                @endif

                <h3>

                    {{ $block['company'] }}

                </h3>

                <p>

                    {{ $block['description'] }}

                </p>

            </div>

            <nav class="footer__menu">

                <h4>

                    Навигация

                </h4>

                @foreach($block['menu'] ?? [] as $item)

                    <a href="{{ $item['url'] }}">

                        {{ $item['title'] }}

                    </a>

                @endforeach

            </nav>

            <div class="footer__contacts">

                <h4>

                    Контакты

                </h4>

                @if(!empty($block['phone']))

                    <a href="tel:{{ $block['phone'] }}">

                        {{ $block['phone'] }}

                    </a>

                @endif

                @if(!empty($block['email']))

                    <a href="mailto:{{ $block['email'] }}">

                        {{ $block['email'] }}

                    </a>

                @endif

                @if(!empty($block['telegram']))

                    <a
                        href="{{ $block['telegram'] }}"
                        target="_blank"
                    >

                        Telegram

                    </a>

                @endif

                @if(!empty($block['whatsapp']))

                    <a
                        href="{{ $block['whatsapp'] }}"
                        target="_blank"
                    >

                        WhatsApp

                    </a>

                @endif

            </div>

        </div>

        <div class="footer__bottom">

            <span>

                {{ $block['copyright'] }}

            </span>

            @if(!empty($block['privacy_url']))

                <a href="{{ $block['privacy_url'] }}">

                    Политика конфиденциальности

                </a>

            @endif

            <button
                class="footer__top"
                type="button"
            >

                ↑

            </button>

        </div>

    </div>

</footer>

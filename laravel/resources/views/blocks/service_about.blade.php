<section class="service-about">

    <div class="container">

        <div class="service-about__grid">

            <div class="service-about__content">

                <span class="service-about__line"></span>

                <p class="service-about__label">
                    Услуга
                </p>

                <h2>
                    {{ $block['title'] ?? 'О направлении' }}
                </h2>

                @if(!empty($block['subtitle']))
                    <h3>
                        {{ $block['subtitle'] }}
                    </h3>
                @endif

                @if(!empty($description))
                    <div class="service-about__text">
                        {!! nl2br(e($description)) !!}
                    </div>
                @endif

            </div>

            <aside class="service-about__aside">

                @if($cover)

                    <div class="service-about__photo">
                        <img
                            src="{{ $cover }}"
                            alt="{{ $service?->title }}"
                        >
                    </div>

                @else

                    <div class="service-about__card">

                        <span class="service-about__line"></span>

                        <h3>
                            Обсудить проект
                        </h3>

                        <p>
                            Расскажем сроки, стоимость и что входит в работы по этому направлению.
                        </p>

                        @if($phone)
                            <a
                                href="tel:{{ preg_replace('/\D/', '', $phone) }}"
                                class="service-about__phone"
                            >
                                {{ $phone }}
                            </a>
                        @endif

                        <div class="service-about__messengers">

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

                        <a
                            href="#"
                            class="hero__button service-about__button"
                            data-open-lead
                        >
                            Оставить заявку
                        </a>

                    </div>

                @endif

            </aside>

        </div>

    </div>

</section>

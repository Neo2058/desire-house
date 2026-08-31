<section class="service-about project-about">

    <div class="container">

        <div class="service-about__grid">

            <div class="service-about__content">

                <span class="service-about__line"></span>

                <p class="service-about__label">
                    Проект
                </p>

                <h2>
                    {{ $block['title'] ?? 'О объекте' }}
                </h2>

                @if($project?->city || $project?->area)
                    <div class="project-about__facts">

                        @if($project?->city)
                            <div class="project-about__fact">
                                <span>Город</span>
                                <strong>{{ $project->city }}</strong>
                            </div>
                        @endif

                        @if($project?->area)
                            <div class="project-about__fact">
                                <span>Площадь</span>
                                <strong>{{ $project->area_label }}</strong>
                            </div>
                        @endif

                    </div>
                @endif

                @if($services->isNotEmpty())
                    <div class="project-about__services">
                        @foreach($services as $item)
                            <a href="{{ url('/uslugi/'.$item->slug) }}">
                                {{ $item->title }}
                            </a>
                        @endforeach
                    </div>
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
                            alt="{{ $project?->title }}"
                        >
                    </div>

                @else

                    <div class="service-about__card">

                        <span class="service-about__line"></span>

                        <h3>
                            Обсудить проект
                        </h3>

                        <p>
                            Хотите такой же объект? Расскажем по срокам, стоимости и этапам работ.
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

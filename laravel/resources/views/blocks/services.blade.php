@php
    use Illuminate\Support\Str;
@endphp

<section class="services">
    <div class="container">
        <header class="services__header">
            <span class="services__eyebrow">Услуги Desire House</span>

            @if(!empty($block['title']))
                <h2>{{ $block['title'] }}</h2>
            @endif

            @if(!empty($block['subtitle']))
                <p>{{ $block['subtitle'] }}</p>
            @endif
        </header>

        @if($services->isNotEmpty())
            <div class="services__grid">
                @foreach($services as $service)
                    @php
                        $cover = $service->projects
                            ->first(fn ($project) => $project->getFirstMediaUrl('cover'))
                            ?->getFirstMediaUrl('cover');
                        $description = $service->short_description ?: $service->description;
                    @endphp

                    <article class="service-card">
                        <a
                            href="{{ url('/uslugi/'.$service->slug) }}"
                            class="service-card__link"
                            aria-label="Подробнее об услуге {{ $service->title }}"
                        >
                            <div class="service-card__media">
                                @if($cover)
                                    <img
                                        src="{{ $cover }}"
                                        alt="Выполненные работы: {{ $service->title }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="service-card__placeholder" aria-hidden="true">
                                        <span>DH</span>
                                    </div>
                                @endif

                                <span class="service-card__number">
                                    {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            <div class="service-card__body">
                                <h3>{{ $service->title }}</h3>

                                @if($description)
                                    <p>{{ Str::limit(strip_tags($description), 145) }}</p>
                                @endif

                                <span class="service-card__action">
                                    Смотреть услугу <span aria-hidden="true">→</span>
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <p class="services__empty">Список услуг скоро будет опубликован.</p>
        @endif

        @if(!empty($block['button_text']) && !empty($block['button_url']))
            <div class="services__footer">
                <a href="{{ $block['button_url'] }}" class="services__button">
                    {{ $block['button_text'] }}
                </a>
            </div>
        @endif
    </div>
</section>

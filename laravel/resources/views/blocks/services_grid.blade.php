@php
    use Illuminate\Support\Str;
@endphp

<section class="services-grid">

    <div class="container">

        @if(!empty($block['title']))
            <h2 class="services-grid__title">
                {{ $block['title'] }}
            </h2>
        @endif

        @if(!empty($block['subtitle']))
            <p class="services-grid__subtitle">
                {{ $block['subtitle'] }}
            </p>
        @endif

        <div class="services-grid__items">

            @foreach($services as $service)

                <a
                    href="/uslugi/{{ $service->slug }}"
                    class="services-grid__card"
                >

                    @if($service->cover)

                        <img
                            src="{{ $service->cover->url }}"
                            alt="{{ $service->title }}"
                        >

                    @endif

                    <div class="services-grid__content">

                        <h3>
                            {{ $service->title }}
                        </h3>

                        @if(!empty($block['show_description']))
                            <p>
                                {{ Str::limit(strip_tags($service->description), 120) }}
                            </p>
                        @endif

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</section>

<section class="features">

    <div class="container">

        @if(!empty($block['title']))
            <div class="features__heading">
                <span class="features__line"></span>
                <h2>{{ $block['title'] }}</h2>
            </div>
        @endif

        <div class="features__grid">

            @foreach($block['items'] ?? [] as $item)

                <article class="feature-card">

                    @if(!empty($item['icon']))
                        <div class="feature-card__icon">
                            {!!
                                file_get_contents(Storage::disk('public')->path($item['icon']))
                            !!}
                        </div>
                    @endif

                    <div class="feature-card__content">

                        <h3>
                            {{ $item['title'] }}
                        </h3>

                        @if(!empty($item['description']))
                            <p>
                                {{ $item['description'] }}
                            </p>
                        @endif

                    </div>

                </article>

            @endforeach

        </div>

    </div>

</section>

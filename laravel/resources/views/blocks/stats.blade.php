<section class="stats">

    <div class="container">

        <div class="stats__wrapper">

            {{-- Фото --}}
            <div class="stats__photo">

                <img
                    src="{{ asset('images/stats/person.png') }}"
                    alt="{{ $block['title'] ?? '' }}">

            </div>

            {{-- Контент --}}
            <div class="stats__content">

                <span class="stats__line"></span>

                <h2>
                    {{ $block['title'] ?? '' }}
                </h2>

                @if(!empty($block['subtitle']))
                    <h3>
                        {{ $block['subtitle'] }}
                    </h3>
                @endif

                @if(!empty($block['description']))
                    <p class="stats__description">
                        {!! nl2br(e($block['description'])) !!}
                    </p>
                @endif

                <div class="stats__signature">

                    <img
                        src="{{ asset('images/stats/signature.png') }}"
                        alt="Подпись">

                </div>

            </div>

            {{-- Правая колонка --}}
            <div class="stats__numbers">

                @foreach($block['items'] ?? [] as $index => $item)

                    <div
                        class="stats__card"
                        style="--i: {{ $index }}">

                        <div class="stats__number">
                            {{ $item['number'] }}
                        </div>

                        <div class="stats__label">
                            {{ $item['label'] }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</section>

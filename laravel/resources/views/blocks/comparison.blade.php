<section class="comparison">

    <div class="container">

        {{-- HEADER --}}
        <div class="comparison__header">

            <span class="comparison__line"></span>

            <h2>{{ $block['title'] ?? '' }}</h2>

        </div>

        <div class="comparison__wrapper">

            {{-- LEFT --}}
            <div class="comparison__side comparison__side--left">

                <div class="comparison__bg"></div>

                <div class="comparison__content">

                    <h3>{{ $block['left_title'] ?? '' }}</h3>

                    <ul>

                        @foreach($block['left_items'] ?? [] as $index => $item)

                            <li class="comparison__item" style="--i: {{ $index }}">

                                ❌ {{ $item['text'] }}

                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

            {{-- VS --}}
            <div class="comparison__vs">

                <span>VS</span>

            </div>

            {{-- RIGHT --}}
            <div class="comparison__side comparison__side--right">

                <div class="comparison__bg"></div>

                <div class="comparison__content">

                    <h3>{{ $block['right_title'] ?? '' }}</h3>

                    <ul>

                        @foreach($block['right_items'] ?? [] as $index => $item)

                            <li class="comparison__item comparison__item--good" style="--i: {{ $index }}">

                                {{ $item['text'] }} ✔

                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    </div>

</section>

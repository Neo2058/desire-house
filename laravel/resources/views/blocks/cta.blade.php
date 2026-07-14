<section class="cta">

    <div class="cta__background"

         @if(!empty($block['background']))
             style="background-image:url('{{ asset('storage/'.$block['background']) }}')"
        @endif>

    </div>


    <div class="container">

        <div class="cta__content">

            <div class="cta__left">

                <span class="cta__line"></span>

                <h2>
                    {{ $block['title'] }}
                </h2>

                @if(!empty($block['subtitle']))
                    <h3>
                        {{ $block['subtitle'] }}
                    </h3>
                @endif

                @if(!empty($block['description']))
                    <p>
                        {{ $block['description'] }}
                    </p>
                @endif

            </div>

            <div class="cta__right">

                <form class="cta-form">

                    <input
                        type="text"
                        name="name"
                        placeholder="Ваше имя"
                    >

                    <input
                        type="tel"
                        name="phone"
                        placeholder="Телефон"
                    >

                    <select name="object_type">

                        @foreach($block['object_types'] ?? [] as $type)

                            <option value="{{ $type }}">
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    <button type="submit">

                        {{ $block['button_text'] ?? 'Получить расчёт' }}

                    </button>

                </form>

            </div>

        </div>

    </div>


</section>

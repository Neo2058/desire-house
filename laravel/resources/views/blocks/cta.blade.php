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

                <x-lead-form
                    theme="dark"
                    :object-types="$block['object_types'] ?? []"
                    :button-text="$block['button_text'] ?? 'Получить расчёт'"
                    :source="$block['source'] ?? 'cta'"
                />

            </div>

        </div>

    </div>


</section>

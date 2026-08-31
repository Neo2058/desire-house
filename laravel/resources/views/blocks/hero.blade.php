<section class="hero"
         @if($background)
             style="background-image:url('{{ $background }}')"
          @endif
>

    <div class="hero__overlay"></div>

    <x-site-header
        :logo="$logo ?? null"
        :settings="$settings ?? null"
        :menu="$menu ?? []"
        :phone="$phone ?? null"
        :telegram="$telegram ?? null"
        :whatsapp="$whatsapp ?? null"
    />

    <div class="container hero__content">

        <div class="hero__left">

            @if(!empty($block['subtitle']))
                <div class="hero__subtitle">

                    {{ $block['subtitle'] }}

                </div>
            @endif

            <h1 class="hero__title">

                {{ $block['title'] }}

            </h1>

            @if(!empty($block['description']))
                <div class="hero__description">

                    {{ $block['description'] }}

                </div>
            @endif

            @if(!empty($block['button_text']))
                    <a
                        href="#"
                        class="hero__button"
                        data-open-lead
                    >
                        {{ $block['button_text'] }}
                    </a>
            @endif

        </div>

        <div class="hero__right">

            @if($person)

                <img
                    src="{{ $person }}"
                    alt=""
                >

            @endif

        </div>

    </div>

</section>

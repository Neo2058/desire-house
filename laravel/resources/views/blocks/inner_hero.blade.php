<section
    class="inner-hero"
    @if($background)
        style="background-image:url('{{ $background }}')"
    @endif
>

    <div class="inner-hero__overlay"></div>

    <x-site-header
        :logo="$logo ?? null"
        :settings="$settings ?? null"
        :menu="$menu ?? []"
        :phone="$phone ?? null"
        :telegram="$telegram ?? null"
        :whatsapp="$whatsapp ?? null"
    />

    <div class="container">

        @if(!empty($block['show_breadcrumbs']))

            <nav class="breadcrumbs">

                <a href="{{ url('/') }}">
                    Главная
                </a>

                @if(!empty($block['breadcrumb_parent_title']))

                    <span>/</span>

                    <a href="{{ url($block['breadcrumb_parent_url'] ?? '/uslugi') }}">
                        {{ $block['breadcrumb_parent_title'] }}
                    </a>

                @endif

                <span>/</span>

                <span>
                    {{ $block['title'] }}
                </span>

            </nav>

        @endif

        <h1 class="inner-hero__title">

            {{ $block['title'] }}

        </h1>

        @if(!empty($block['subtitle']))

            <p class="inner-hero__subtitle">

                {{ $block['subtitle'] }}

            </p>

        @endif

    </div>

</section>

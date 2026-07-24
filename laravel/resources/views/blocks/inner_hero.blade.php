<section
    class="inner-hero"
    @if($background)
        style="background-image:url('{{ $background }}')"
    @endif
>

    <div class="inner-hero__overlay"></div>

    <div class="container">

        @if(!empty($block['show_breadcrumbs']))

            <nav class="breadcrumbs">

                <a href="/">
                    Главная
                </a>

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

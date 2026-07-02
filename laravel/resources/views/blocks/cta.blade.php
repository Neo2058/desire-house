<section class="cta">
    <h2>{{ $block['title'] ?? '' }}</h2>

    <p>
        {{ $block['description'] ?? '' }}
    </p>

    @if(!empty($block['button_text']))
        <a href="{{ $block['button_url'] }}">
            {{ $block['button_text'] }}
        </a>
    @endif
</section>

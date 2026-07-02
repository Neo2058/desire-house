<section class="features">
    <h2>{{ $block['title'] ?? '' }}</h2>

    @foreach($block['items'] ?? [] as $item)
        <div>
            <h3>{{ $item['title'] }}</h3>

            <p>
                {{ $item['description'] }}
            </p>
        </div>
    @endforeach
</section>

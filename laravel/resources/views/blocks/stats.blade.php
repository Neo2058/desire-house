<section class="stats">
    <h2>{{ $block['title'] ?? '' }}</h2>

    @foreach($block['items'] ?? [] as $item)
        <div>
            <strong>{{ $item['number'] }}</strong>
            <p>{{ $item['label'] }}</p>
        </div>
    @endforeach
</section>

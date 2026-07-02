<section>

    <h2>{{ $block['title'] ?? '' }}</h2>

    <p>{{ $block['subtitle'] ?? '' }}</p>

    @foreach($gallery->getMedia('gallery') as $image)

        <img
            src="{{ $image->getUrl() }}"
            alt="{{ $gallery->title }}"
            width="250"
        >

    @endforeach

</section>

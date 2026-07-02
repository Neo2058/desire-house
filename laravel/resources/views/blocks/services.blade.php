<section>

    <h2>{{ $block['title'] }}</h2>

    @if(!empty($block['subtitle']))
        <p>{{ $block['subtitle'] }}</p>
    @endif

    @foreach($services as $service)

        <article>

            <h3>{{ $service->title }}</h3>

            <p>{{ $service->description }}</p>

        </article>

    @endforeach

</section>

<section>

    <h2>{{ $block['title'] }}</h2>

    @if(!empty($block['subtitle']))
        <p>{{ $block['subtitle'] }}</p>
    @endif

    @foreach($projects as $project)

        <a href="{{ url('/raboty/'.$project->slug) }}">
            <article>

                @if($project->getFirstMediaUrl('cover'))
                    <img
                        src="{{ $project->getFirstMediaUrl('cover') }}"
                        alt="{{ $project->title }}"
                        width="250"
                    >
                @endif

                <h3>{{ $project->title }}</h3>

                <p>{{ $project->city }}</p>

                <p>{{ $project->area_label }}</p>

            </article>
        </a>

    @endforeach

</section>

<section class="works-gallery">

    <div class="container">

        @if($groups->count() > 1)
            <nav class="works-gallery__nav">
                @foreach($groups as $group)
                    <a href="#works-{{ $group['slug'] }}">
                        {{ $group['title'] }}
                    </a>
                @endforeach
            </nav>
        @endif

        @forelse($groups as $group)

            <section
                class="works-gallery__group"
                id="works-{{ $group['slug'] }}"
            >

                <div class="works-gallery__header">

                    <div class="works-gallery__heading">
                        <span class="works-gallery__line"></span>
                        <h2>{{ $group['title'] }}</h2>
                    </div>

                    @if(!empty($group['url']))
                        <a
                            href="{{ url($group['url']) }}"
                            class="works-gallery__service"
                        >
                            Об услуге →
                        </a>
                    @endif

                </div>

                <div class="works-gallery__grid">

                    @foreach($group['projects'] as $project)

                        <article class="project-card">

                            <a
                                href="{{ url('/raboty/'.$project->slug) }}"
                                class="project-card__link"
                            >

                                <div class="project-card__image">

                                    @if($project->getFirstMediaUrl('cover'))
                                        <img
                                            src="{{ $project->getFirstMediaUrl('cover') }}"
                                            alt="{{ $project->title }}"
                                        >
                                    @endif

                                </div>

                                <div class="project-card__overlay">

                                    <div class="project-card__content">

                                        <h3>{{ $project->title }}</h3>

                                        @if($project->city)
                                            <p>{{ $project->city }}</p>
                                        @endif

                                    </div>

                                </div>

                            </a>

                        </article>

                    @endforeach

                </div>

            </section>

        @empty

            <p class="works-gallery__empty">
                Скоро здесь появятся наши объекты.
            </p>

        @endforelse

    </div>

</section>

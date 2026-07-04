<section class="projects">

    <div class="container">

        <div class="projects__header">

            <div class="projects__heading">

                <span class="projects__line"></span>

                <h2>{{ $block['title'] }}</h2>

                @if(!empty($block['subtitle']))
                    <p>{{ $block['subtitle'] }}</p>
                @endif

            </div>

            <a
                href="{{ url('/raboty') }}"
                class="projects__all"
            >
                Смотреть все работы →
            </a>

        </div>

        <div class="swiper projects-swiper">

            <div class="swiper-wrapper">

                @foreach($projects as $project)

                    <div class="swiper-slide">

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

                                        <h3>

                                            {{ $project->title }}

                                        </h3>

                                        <p>

                                            {{ $project->city }}

                                        </p>

                                    </div>

                                </div>

                            </a>

                        </article>

                    </div>

                @endforeach

            </div>

        </div>

        <div class="projects__navigation">

            <button class="projects__button projects-prev">

                ←

            </button>

            <button class="projects__button projects-next">

                →

            </button>

        </div>

    </div>

</section>

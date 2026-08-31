@if($images->isNotEmpty())
<section class="project-gallery">

    <div class="container">

        <div class="project-gallery__heading">
            <span class="project-gallery__line"></span>
            <h2>{{ $block['title'] ?? 'Фото объекта' }}</h2>
        </div>

        <div @class([
            'project-gallery__grid',
            'is-featured' => $images->count() >= 3,
        ])>

            @foreach($images as $image)

                <button
                    type="button"
                    class="project-gallery__item"
                    data-gallery-src="{{ $image->getUrl() }}"
                    data-gallery-alt="{{ $block['title'] ?? 'Фото объекта' }}"
                >
                    <img
                        src="{{ $image->getUrl() }}"
                        alt="{{ $block['title'] ?? 'Фото объекта' }}"
                    >
                </button>

            @endforeach

        </div>

    </div>

    <div
        class="project-gallery__lightbox"
        id="project-gallery-lightbox"
        hidden
    >
        <div class="project-gallery__lightbox-overlay"></div>
        <button
            type="button"
            class="project-gallery__lightbox-close"
            aria-label="Закрыть"
        >
            ×
        </button>
        <img alt="">
    </div>

</section>
@endif

document.addEventListener('DOMContentLoaded', () => {

    const lightbox = document.getElementById('project-gallery-lightbox');

    if (!lightbox) {
        return;
    }

    const image = lightbox.querySelector('img');
    const overlay = lightbox.querySelector('.project-gallery__lightbox-overlay');
    const close = lightbox.querySelector('.project-gallery__lightbox-close');
    const triggers = document.querySelectorAll('[data-gallery-src]');

    function openLightbox(src, alt) {

        image.src = src;
        image.alt = alt || '';
        lightbox.hidden = false;
        document.body.style.overflow = 'hidden';

    }

    function closeLightbox() {

        lightbox.hidden = true;
        image.src = '';
        document.body.style.overflow = '';

    }

    triggers.forEach((trigger) => {

        trigger.addEventListener('click', () => {

            openLightbox(
                trigger.getAttribute('data-gallery-src'),
                trigger.getAttribute('data-gallery-alt'),
            );

        });

    });

    close.addEventListener('click', closeLightbox);
    overlay.addEventListener('click', closeLightbox);

    document.addEventListener('keydown', (event) => {

        if (event.key === 'Escape' && !lightbox.hidden) {
            closeLightbox();
        }

    });

});

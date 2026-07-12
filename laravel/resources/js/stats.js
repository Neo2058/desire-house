document.addEventListener('DOMContentLoaded', () => {

    const section = document.querySelector('.stats');

    if (!section) {
        return;
    }

    const photo = section.querySelector('.stats__photo');
    const content = section.querySelector('.stats__content');
    const cards = section.querySelectorAll('.stats__card');

    let animated = false;

    const observer = new IntersectionObserver(entries => {

        entries.forEach(entry => {

            if (!entry.isIntersecting || animated) {
                return;
            }

            animated = true;

            photo?.classList.add('is-visible');

            content?.classList.add('is-visible');

            cards.forEach((card, index) => {

                setTimeout(() => {

                    card.classList.add('is-visible');

                    const number = card.querySelector('.stats__number');

                    animateCounter(number);

                }, index * 180);

            });

            observer.unobserve(section);

        });

    }, {

        threshold: 0.35,

    });

    observer.observe(section);

});


function animateCounter(element) {

    if (!element) {
        return;
    }

    const text = element.textContent.trim();

    const value = parseInt(text.replace(/\D/g, ''));

    if (isNaN(value)) {
        return;
    }

    const prefix = text.match(/^[^\d]+/)?.[0] ?? '';

    const suffix = text.match(/[^\d]+$/)?.[0] ?? '';

    const duration = 1800;

    const start = performance.now();

    function frame(now) {

        const progress = Math.min((now - start) / duration, 1);

        const eased = 1 - Math.pow(1 - progress, 3);

        const current = Math.floor(value * eased);

        element.textContent = prefix + current + suffix;

        if (progress < 1) {

            requestAnimationFrame(frame);

        } else {

            element.textContent = text;

        }

    }

    requestAnimationFrame(frame);

}

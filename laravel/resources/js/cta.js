document.addEventListener('DOMContentLoaded', () => {

    const section = document.querySelector('.cta');

    if (!section) {
        return;
    }

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (!entry.isIntersecting) {
                return;
            }

            section.classList.add('is-visible');

            observer.disconnect();

        });

    }, {
        threshold: 0.35
    });

    observer.observe(section);

});

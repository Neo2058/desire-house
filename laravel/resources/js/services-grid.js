document.addEventListener('DOMContentLoaded', () => {

    const section = document.querySelector('.services-grid');

    if (!section) return;

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (!entry.isIntersecting) return;

            section.classList.add('is-visible');

            observer.disconnect();

        });

    }, {

        threshold: 0.25,

    });

    observer.observe(section);

});

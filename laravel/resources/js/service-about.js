document.addEventListener('DOMContentLoaded', () => {

    const sections = document.querySelectorAll('.service-about');

    if (!sections.length) {
        return;
    }

    const observer = new IntersectionObserver((entries) => {

        entries.forEach((entry) => {

            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');

            observer.unobserve(entry.target);

        });

    }, {

        threshold: 0.25,

    });

    sections.forEach((section) => observer.observe(section));

});

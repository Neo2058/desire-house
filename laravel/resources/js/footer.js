document.addEventListener('DOMContentLoaded', () => {

    const footer = document.querySelector('.footer');

    if (!footer) return;

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (!entry.isIntersecting) return;

            footer.classList.add('is-visible');

            observer.disconnect();

        });

    }, {

        threshold: 0.2

    });

    observer.observe(footer);

    const button = document.querySelector('.footer__top');

    if (button) {

        button.addEventListener('click', () => {

            window.scrollTo({

                top: 0,

                behavior: 'smooth'

            });

        });

    }

});

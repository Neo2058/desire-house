import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import './comparison';
import './stats.js';

import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {

    const cards = document.querySelectorAll('.feature-card');

    const observer = new IntersectionObserver((entries) => {

        entries.forEach((entry, index) => {

            if (!entry.isIntersecting) {
                return;
            }

            setTimeout(() => {

                entry.target.classList.add('is-visible');

            }, index * 180);

            observer.unobserve(entry.target);

        });

    }, {

        threshold: .35,

    });

    cards.forEach(card => observer.observe(card));

});


document.addEventListener('DOMContentLoaded', () => {

    const cards = document.querySelectorAll('.project-card');

    const observer = new IntersectionObserver((entries) => {

        entries.forEach((entry) => {

            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');

            observer.unobserve(entry.target);

        });

    }, {

        threshold: .2,

    });

    cards.forEach(card => observer.observe(card));

    new Swiper('.projects-swiper', {

        modules: [Navigation],

        slidesPerView: 2,

        spaceBetween: 24,

        navigation: {

            nextEl: '.projects-next',

            prevEl: '.projects-prev',

        },

        breakpoints: {

            0: {

                slidesPerView: 1,

            },

            900: {

                slidesPerView: 2,

            }

        }

    });

});


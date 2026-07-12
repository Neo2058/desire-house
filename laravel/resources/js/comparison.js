document.addEventListener('DOMContentLoaded', () => {

    const blocks = document.querySelectorAll('.comparison');

    blocks.forEach(block => {

        const wrapper = block.querySelector('.comparison__wrapper');

        const sides = block.querySelectorAll('.comparison__side');


        const observer = new IntersectionObserver(entries => {

            entries.forEach(entry => {

                if (!entry.isIntersecting) {
                    return;
                }


                sides.forEach(side => {

                    side.classList.add('is-visible');

                });


                wrapper.classList.add('is-visible');


                observer.unobserve(block);

            });

        }, {

            threshold:0.3

        });


        observer.observe(block);

    });

});

document.addEventListener('DOMContentLoaded', () => {

    const modal = document.querySelector('#lead-modal');

    if (!modal) {
        return;
    }

    const overlay = modal.querySelector('.lead-modal__overlay');

    const close = modal.querySelector('.lead-modal__close');

    function openModal(objectType = '') {

        const select = modal.querySelector('[name="object_type"]');

        if (select && objectType) {

            let option = Array.from(select.options)
                .find(item => item.value === objectType);

            if (!option) {
                option = new Option(objectType, objectType);
                select.add(option);
            }

            select.value = objectType;

        }

        modal.classList.add('active');

        document.body.style.overflow = 'hidden';

    }

    function closeModal() {

        modal.classList.remove('active');

        document.body.style.overflow = '';

    }

    document.querySelectorAll('[data-open-lead]').forEach(button => {

        button.addEventListener('click', e => {

            e.preventDefault();

            openModal(button.dataset.leadObject ?? '');

        });

    });

    overlay.addEventListener('click', closeModal);

    close.addEventListener('click', closeModal);

    document.addEventListener('keydown', e => {

        if (e.key === 'Escape') {

            closeModal();

        }

    });

    window.LeadModal = {

        open: openModal,

        close: closeModal,

    };

});

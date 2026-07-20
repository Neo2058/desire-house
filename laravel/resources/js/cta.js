document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Анимация CTA
    |--------------------------------------------------------------------------
    */

    const section = document.querySelector('.cta');

    if (section) {

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

    }

    /*
    |--------------------------------------------------------------------------
    | Формы заявок
    |--------------------------------------------------------------------------
    */

    const forms = document.querySelectorAll('.cta-form');

    if (!forms.length) {
        return;
    }

    function clearErrors(form) {

        form.querySelectorAll('.form-group').forEach(group => {

            group.classList.remove('error');
            group.classList.remove('success');

            const error = group.querySelector('.form-error');

            if (error) {
                error.textContent = '';
            }

        });

    }

    function showError(input, message) {

        const group = input.closest('.form-group');

        if (!group) {
            return;
        }

        group.classList.add('error');

        const error = group.querySelector('.form-error');

        if (error) {
            error.textContent = message;
        }

    }

    function showSuccess(input) {

        const group = input.closest('.form-group');

        if (!group) {
            return;
        }

        group.classList.add('success');

    }

    function validateForm(form) {

        clearErrors(form);

        let valid = true;

        const name = form.querySelector('[name="name"]');
        const phone = form.querySelector('[name="phone"]');
        const objectType = form.querySelector('[name="object_type"]');

        /*
        |--------------------------------------------------------------------------
        | Имя
        |--------------------------------------------------------------------------
        */

        if (name) {

            const value = name.value.trim();

            const regex = /^[A-Za-zА-Яа-яЁё\s-]+$/u;

            if (value.length < 2) {

                showError(name, 'Введите имя.');

                valid = false;

            } else if (!regex.test(value)) {

                showError(name, 'Имя может содержать только буквы.');

                valid = false;

            } else {

                showSuccess(name);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Телефон
        |--------------------------------------------------------------------------
        */

        if (phone) {

            const digits = phone.value.replace(/\D/g, '');

            if (digits.length !== 11 || !digits.startsWith('7')) {

                showError(phone, 'Введите корректный номер телефона.');

                valid = false;

            } else {

                showSuccess(phone);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Тип строительства
        |--------------------------------------------------------------------------
        */

        if (objectType) {

            if (!objectType.value) {

                showError(objectType, 'Выберите тип строительства.');

                valid = false;

            } else {

                showSuccess(objectType);

            }

        }

        return valid;

    }

    /*
    |--------------------------------------------------------------------------
    | Подключаем каждую форму отдельно
    |--------------------------------------------------------------------------
    */

    forms.forEach(form => {

        const phoneInput = form.querySelector('[name="phone"]');

        /*
        |--------------------------------------------------------------------------
        | Маска телефона
        |--------------------------------------------------------------------------
        */

        if (phoneInput) {

            phoneInput.addEventListener('input', () => {

                let value = phoneInput.value.replace(/\D/g, '');

                if (value.startsWith('8')) {
                    value = '7' + value.slice(1);
                }

                if (!value.startsWith('7')) {
                    value = '7' + value;
                }

                value = value.substring(0, 11);

                let result = '+7';

                if (value.length > 1) {
                    result += ' (' + value.substring(1, 4);
                }

                if (value.length >= 5) {
                    result += ') ' + value.substring(4, 7);
                }

                if (value.length >= 8) {
                    result += '-' + value.substring(7, 9);
                }

                if (value.length >= 10) {
                    result += '-' + value.substring(9, 11);
                }

                phoneInput.value = result;

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Очистка ошибок при вводе
        |--------------------------------------------------------------------------
        */

        form.querySelectorAll('input, select').forEach(field => {

            field.addEventListener('input', () => {

                const group = field.closest('.form-group');

                if (!group) {
                    return;
                }

                group.classList.remove('error');
                group.classList.remove('success');

                const error = group.querySelector('.form-error');

                if (error) {
                    error.textContent = '';
                }

            });

        });

        /*
        |--------------------------------------------------------------------------
        | Отправка формы
        |--------------------------------------------------------------------------
        */

        form.addEventListener('submit', async (e) => {

            e.preventDefault();

            if (!validateForm(form)) {
                return;
            }

            const button = form.querySelector('button');

            button.classList.add('loading');
            button.disabled = true;

            const data = new FormData(form);

            data.set(
                'phone',
                data.get('phone').replace(/\D/g, '')
            );

            try {

                const response = await fetch('/lead', {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content,

                        'Accept': 'application/json'

                    },

                    body: data

                });

                if (response.status === 422) {

                    const errors = await response.json();

                    clearErrors(form);

                    Object.entries(errors.errors).forEach(([field, messages]) => {

                        const input = form.querySelector(`[name="${field}"]`);

                        if (input) {
                            showError(input, messages[0]);
                        }

                    });

                    return;

                }

                if (!response.ok) {
                    throw new Error('Server error');
                }

                const result = await response.json();

                if (result.success) {

                    clearErrors(form);

                    form.reset();

                    showLeadMessage(result.message);

                    if (window.LeadModal) {
                        window.LeadModal.close();
                    }

                }

            } catch (error) {

                console.error(error);

            } finally {

                button.classList.remove('loading');
                button.disabled = false;

            }

        });

    });

});

/*
|--------------------------------------------------------------------------
| Успешная отправка
|--------------------------------------------------------------------------
*/

function showLeadMessage(message) {

    const box = document.createElement('div');

    box.className = 'lead-success';

    box.innerHTML = message;

    document.body.append(box);

    setTimeout(() => {

        box.classList.add('show');

    }, 50);

    setTimeout(() => {

        box.classList.remove('show');

        setTimeout(() => {

            box.remove();

        }, 300);

    }, 4000);

}

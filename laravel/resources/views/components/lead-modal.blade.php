<div
    id="lead-modal"
    class="lead-modal"
>

    <div class="lead-modal__overlay"></div>

    <div class="lead-modal__window">

        <button
            class="lead-modal__close"
            type="button"
        >
            ×
        </button>

        <div class="lead-modal__header">

            <h2>
                Получить консультацию
            </h2>

            <p>
                Оставьте заявку и мы свяжемся с вами в ближайшее время.
            </p>

        </div>

        <x-lead-form
            theme="light"
            source="hero"
            :object-types="[
        'Дом',
        'Фундамент',
        'Баня',
        'Гараж',
        'Пристройка',
        'Терраса',
        'Другое'
    ]"
            button-text="Отправить заявку"
        />

    </div>

</div>

<form
    class="cta-form cta-form--{{ $theme ?? 'dark' }}"
    data-source="{{ $source ?? 'site' }}"
>

    @csrf

    <input
        type="hidden"
        name="source"
        value="{{ $source ?? 'site' }}"
    >

    <div class="form-group">

        <input
            type="text"
            name="name"
            placeholder="Ваше имя"
        >

        <div class="form-error"></div>

    </div>

    <div class="form-group">

        <input
            type="tel"
            name="phone"
            placeholder="Телефон"
            required
        >

        <div class="form-error"></div>

    </div>

    <div class="form-group">

        @if(!empty($objectTypes))

            <select name="object_type">

                @foreach($objectTypes as $type)

                    <option value="{{ $type }}">
                        {{ $type }}
                    </option>

                @endforeach

            </select>

        @endif

        <div class="form-error"></div>

    </div>

    <button type="submit">

        {{ $buttonText ?? 'Отправить заявку' }}

    </button>

</form>

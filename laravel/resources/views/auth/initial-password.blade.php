<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Смена временного пароля — Desire House CMS</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #111; color: #171717; font: 16px/1.5 Arial, sans-serif; }
        main { width: min(560px, 100%); padding: 42px; background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.35); }
        .brand { color: #ef6b1d; font-size: 13px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 12px 0; font-size: 36px; line-height: 1.1; }
        p { margin: 0 0 26px; color: #666; }
        label { display: block; margin-top: 18px; font-weight: 700; }
        input { width: 100%; margin-top: 8px; padding: 14px 16px; border: 1px solid #d7d7d7; border-radius: 10px; font: inherit; }
        input:focus { outline: 2px solid rgba(239,107,29,.25); border-color: #ef6b1d; }
        .error { margin-top: 7px; color: #b42318; font-size: 14px; }
        .requirements { margin: 20px 0 0; padding: 14px 16px; background: #f5f5f5; border-radius: 10px; color: #555; font-size: 14px; }
        button { width: 100%; margin-top: 28px; padding: 16px; border: 0; border-radius: 10px; background: #ef6b1d; color: #fff; font: inherit; font-weight: 800; cursor: pointer; }
        button:hover { background: #d9560c; }
    </style>
</head>
<body>
<main>
    <span class="brand">Desire House CMS</span>
    <h1>Замените временный пароль</h1>
    <p>Перед началом работы установите собственный пароль администратора.</p>

    <form method="POST" action="{{ route('password.first.update') }}">
        @csrf

        <label>
            Текущий временный пароль
            <input type="password" name="current_password" required autocomplete="current-password" autofocus>
        </label>
        @error('current_password') <div class="error">{{ $message }}</div> @enderror

        <label>
            Новый пароль
            <input type="password" name="password" required autocomplete="new-password">
        </label>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label>
            Подтверждение нового пароля
            <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>

        <div class="requirements">
            Не менее 12 символов: строчные и заглавные буквы, цифры и специальные символы.
        </div>

        <button type="submit">Сохранить новый пароль</button>
    </form>
</main>
</body>
</html>

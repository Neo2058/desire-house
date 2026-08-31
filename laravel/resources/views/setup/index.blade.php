<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Первоначальная настройка — Desire House CMS</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #111; color: #171717; font: 16px/1.5 Arial, sans-serif; }
        main { width: min(560px, 100%); padding: 42px; background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.35); }
        .brand { color: #ef6b1d; font-size: 13px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 12px 0; font-size: 36px; line-height: 1.1; }
        p { margin: 0 0 28px; color: #666; }
        label { display: block; margin-top: 18px; font-weight: 700; }
        input { width: 100%; margin-top: 8px; padding: 14px 16px; border: 1px solid #d7d7d7; border-radius: 10px; font: inherit; }
        input:focus { outline: 2px solid rgba(239,107,29,.25); border-color: #ef6b1d; }
        .error { margin-top: 7px; color: #b42318; font-size: 14px; }
        button { width: 100%; margin-top: 28px; padding: 16px; border: 0; border-radius: 10px; background: #ef6b1d; color: #fff; font: inherit; font-weight: 800; cursor: pointer; }
        button:hover { background: #d9560c; }
        .notice { margin-top: 24px; padding: 14px; background: #fff4ed; color: #8a3b0b; border-radius: 10px; font-size: 14px; }
    </style>
</head>
<body>
<main>
    <span class="brand">Desire House CMS</span>
    <h1>Первоначальная настройка</h1>
    <p>Создайте первого администратора. После завершения этот мастер автоматически закроется.</p>

    <form method="POST" action="{{ route('setup.store') }}">
        @csrf

        <label>
            Имя администратора
            <input name="name" value="{{ old('name') }}" required autocomplete="name">
        </label>
        @error('name') <div class="error">{{ $message }}</div> @enderror

        <label>
            Email для входа
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        </label>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label>
            Установочный токен
            <input type="password" name="install_token" required autocomplete="off">
        </label>
        @error('install_token') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Создать администратора</button>
    </form>

    <div class="notice">Пароль будет показан только один раз. Сохраните его в менеджере паролей.</div>
</main>
</body>
</html>

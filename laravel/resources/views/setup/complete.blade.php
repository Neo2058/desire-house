<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администратор создан — Desire House CMS</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #111; color: #171717; font: 16px/1.5 Arial, sans-serif; }
        main { width: min(620px, 100%); padding: 42px; background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(0,0,0,.35); }
        .status { color: #198754; font-size: 13px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 12px 0; font-size: 36px; }
        p { color: #666; }
        dl { margin: 28px 0; padding: 22px; background: #f5f5f5; border-radius: 12px; }
        dt { color: #777; font-size: 13px; text-transform: uppercase; }
        dd { margin: 5px 0 18px; overflow-wrap: anywhere; font: 700 18px Consolas, monospace; }
        dd:last-child { margin-bottom: 0; }
        a { display: inline-flex; padding: 15px 24px; border-radius: 10px; background: #ef6b1d; color: #fff; font-weight: 800; text-decoration: none; }
        .warning { color: #9a3412; font-weight: 700; }
    </style>
</head>
<body>
<main>
    <span class="status">Настройка завершена</span>
    <h1>Администратор создан</h1>
    <p class="warning">Сохраните данные сейчас: повторно пароль показан не будет.</p>

    <dl>
        <dt>Логин</dt>
        <dd>{{ $login }}</dd>
        <dt>Пароль</dt>
        <dd>{{ $password }}</dd>
    </dl>

    <a href="{{ url('/admin') }}">Перейти в панель администратора</a>
</main>
</body>
</html>

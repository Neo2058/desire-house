<div class="cms-dashboard-welcome">

    <div class="cms-dashboard-welcome__copy">

        <p class="cms-dashboard-welcome__eyebrow">
            Добро пожаловать
        </p>

        <h1 class="cms-dashboard-welcome__title">
            {{ $data['user'] }}
        </h1>

        <p class="cms-dashboard-welcome__subtitle">
            {{ $data['company'] }}
        </p>

    </div>

    <div class="cms-dashboard-welcome__date">
        <span class="cms-dashboard-welcome__date-label">Сегодня</span>
        <span class="cms-dashboard-welcome__date-value">{{ $data['date'] }}</span>
    </div>

</div>

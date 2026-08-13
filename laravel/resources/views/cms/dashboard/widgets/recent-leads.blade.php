<div class="cms-dashboard-panel">

    <div class="cms-dashboard-panel__header">

        <div>
            <h2 class="cms-dashboard-panel__title">{{ $widget::title() }}</h2>
            <p class="cms-dashboard-panel__hint">Последние обращения с сайта</p>
        </div>

        <a href="{{ \App\Filament\Admin\Resources\Leads\LeadResource::getUrl('index') }}" class="cms-dashboard-panel__link">
            Все заявки
        </a>

    </div>

    @if(empty($data))

        <div class="cms-dashboard-empty">

            <x-heroicon-o-inbox class="cms-dashboard__empty-icon" />

            <p class="cms-dashboard-empty__title">Пока нет заявок</p>
            <p class="cms-dashboard-empty__text">Новые обращения клиентов появятся здесь.</p>

        </div>

    @else

        <div class="cms-dashboard-leads">

            @foreach($data as $lead)

                <a href="{{ $lead['href'] }}" class="cms-dashboard-lead">

                    <span class="cms-dashboard-lead__avatar">
                        {{ $lead['initials'] }}
                    </span>

                    <span class="cms-dashboard-lead__main">
                        <span class="cms-dashboard-lead__name">{{ $lead['name'] }}</span>
                        <span class="cms-dashboard-lead__phone">{{ $lead['phone'] }}</span>
                    </span>

                    <span class="cms-dashboard-lead__meta">
                        @if($lead['object_type'])
                            <span class="cms-dashboard-lead__type">{{ $lead['object_type'] }}</span>
                        @endif
                        <span class="cms-dashboard-lead__time">
                            {{ \Illuminate\Support\Carbon::parse($lead['created_at'])->diffForHumans() }}
                        </span>
                    </span>

                </a>

            @endforeach

        </div>

    @endif

</div>

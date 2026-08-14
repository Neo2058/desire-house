<div class="cms-dashboard-panel">

    <div class="cms-dashboard-panel__header">
        <div>
            <h2 class="cms-dashboard-panel__title">{{ $widget::title() }}</h2>
            <p class="cms-dashboard-panel__hint">Частые действия в CMS</p>
        </div>
    </div>

    <div class="cms-dashboard-actions">

        @foreach($data as $action)

            <a href="{{ $action['href'] }}" class="cms-dashboard-action">

                <span class="cms-dashboard-action__icon">

                    @switch($action['icon'])

                        @case('plus')
                            <x-heroicon-o-plus class="cms-dashboard__action-icon" />
                            @break

                        @case('service')
                            <x-heroicon-o-wrench-screwdriver class="cms-dashboard__action-icon" />
                            @break

                        @case('project')
                            <x-heroicon-o-building-office-2 class="cms-dashboard__action-icon" />
                            @break

                    @endswitch

                </span>

                <span class="cms-dashboard-action__copy">
                    <span class="cms-dashboard-action__label">{{ $action['label'] }}</span>
                    <span class="cms-dashboard-action__hint">{{ $action['hint'] }}</span>
                </span>

                <x-heroicon-o-chevron-right class="cms-dashboard-action__chevron" />

            </a>

        @endforeach

    </div>

</div>

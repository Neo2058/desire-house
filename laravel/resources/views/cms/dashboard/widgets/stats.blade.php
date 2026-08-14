<div class="cms-dashboard-stats">

    <div class="cms-dashboard-stats__header">
        <h2>{{ $widget::title() }}</h2>
        <p>Ключевые сущности сайта</p>
    </div>

    <div class="cms-dashboard-stats__grid">

        @foreach($data as $item)

            <a href="{{ $item['href'] }}" class="cms-dashboard-stat">

                <div class="cms-dashboard-stat__icon">

                    @switch($item['icon'])

                        @case('pages')
                            <x-heroicon-o-document-text />
                            @break

                        @case('services')
                            <x-heroicon-o-wrench-screwdriver />
                            @break

                        @case('projects')
                            <x-heroicon-o-building-office-2 />
                            @break

                        @case('leads')
                            <x-heroicon-o-inbox />
                            @break

                    @endswitch

                </div>

                <div class="cms-dashboard-stat__content">

                    <div class="cms-dashboard-stat__value">
                        {{ $item['value'] }}
                    </div>

                    <div class="cms-dashboard-stat__label">
                        {{ $item['label'] }}
                    </div>

                </div>

            </a>

        @endforeach

    </div>

</div>

@extends('layouts.app')

@section('title', $service->title)

@section('content')

    @foreach($blocks as $block)
        @includeIf(
            'blocks.' . $block['type'],
            $block['viewData']
        )
    @endforeach

    @if(
        !collect($blocks)->contains('type', 'projects')
        && $service->projects->isNotEmpty()
    )
        @include('blocks.projects', [
            'block' => [
                'title' => 'Выполненные работы',
                'subtitle' => 'Проекты, реализованные в рамках этой услуги',
            ],
            'projects' => $service->projects,
        ])
    @endif

    <section class="service-order">
        <div class="container service-order__inner">
            <div>
                <span class="service-order__eyebrow">Обсудим ваш проект</span>
                <h2>Заказать услугу «{{ $service->title }}»</h2>
                <p>Оставьте заявку — уточним задачу, сроки и подготовим предварительный расчёт.</p>
            </div>

            <button
                type="button"
                class="service-order__button"
                data-open-lead
                data-lead-object="{{ $service->title }}"
            >
                Получить консультацию
            </button>
        </div>
    </section>

@endsection

<div class="cms-dashboard">

    @foreach($widgets as $widget)

        @php
            $instance = app($widget);
            $data = $instance->data();
        @endphp

        <section class="cms-dashboard__widget cms-dashboard__widget--{{ $instance::span() }}">

            @include(
                $instance->view(),
                [
                    'widget' => $instance,
                    'data' => $data,
                ]
            )

        </section>

    @endforeach

</div>
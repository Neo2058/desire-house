<div class="space-y-6">

    @foreach($widgets as $widget)

        @php
            $instance = app($widget);
            $data = $instance->data();
        @endphp

        <div class="rounded-xl border bg-white dark:bg-gray-900 p-6">

            <h2 class="text-xl font-bold">

                {{ $instance::title() }}

            </h2>

            <pre class="mt-4 text-sm">

{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}

            </pre>

        </div>

    @endforeach

</div>

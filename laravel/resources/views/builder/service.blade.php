<x-layouts.app :title="$service->title">

    @foreach($blocks as $block)

        @includeIf(
            'blocks.' . $block['type'],
            $block['viewData']
        )

    @endforeach

</x-layouts.app>

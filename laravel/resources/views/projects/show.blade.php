@extends('layouts.app')

@section('content')

    @foreach($blocks as $block)
        @includeIf(
            'blocks.'.$block['type'],
            $block['viewData']
        )
    @endforeach

@endsection

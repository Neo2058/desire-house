@extends('layouts.app')

@section('title', 'Наши работы')

@section('content')

    @foreach($blocks as $block)
        @includeIf(
            'blocks.'.$block['type'],
            $block['viewData']
        )
    @endforeach

@endsection

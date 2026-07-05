@extends('layouts.app')

@section('title', $service->title)

@section('content')

    @foreach($blocks as $block)
        @includeIf(
            'blocks.' . $block['type'],
            $block['viewData']
        )
    @endforeach

@endsection

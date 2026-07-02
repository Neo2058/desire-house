@extends('layouts.app')

@section('title', $page->title)

@section('content')

    @foreach($blocks as $block)

        @includeIf(
            'blocks.'.$block['type'],
            $block['viewData']
        )

    @endforeach

@endsection

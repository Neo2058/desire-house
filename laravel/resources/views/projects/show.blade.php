@extends('layouts.app')

@section('title', $project->title)

@section('content')

    @foreach($blocks as $block)
        @includeIf(
            'blocks.'.$block['type'],
            $block['viewData']
        )
    @endforeach

@endsection

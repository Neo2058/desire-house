<!DOCTYPE html>
<html>
<head>
    <title>{{ $project->title }}</title>
</head>
<body>

<h1>{{ $project->title }}</h1>

@if($project->getFirstMediaUrl('cover'))

    <img
        src="{{ $project->getFirstMediaUrl('cover') }}"
        alt="{{ $project->title }}"
        width="500"
    >

@endif

@if($project->city)
    <p><strong>Город:</strong> {{ $project->city }}</p>
@endif

@if($project->area)
    <p><strong>Площадь:</strong> {{ $project->area }} м²</p>
@endif

@if($project->description)
    <p>{{ $project->description }}</p>
@endif

@if($project->getMedia('gallery')->count())

    <h2>Галерея</h2>

    @foreach($project->getMedia('gallery') as $image)

        <img
            src="{{ $image->getUrl() }}"
            width="300"
            alt=""
        >

    @endforeach

@endif

</body>
</html>

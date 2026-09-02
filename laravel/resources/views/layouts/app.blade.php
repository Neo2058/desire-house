<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    {!! seo($seo ?? null) !!}

    <link
        rel="alternate"
        type="text/plain"
        href="{{ url('/llms.txt') }}"
        title="LLM information"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-900 antialiased">

<main>
    @yield('content')
</main>

<x-lead-modal />
</body>
</html>

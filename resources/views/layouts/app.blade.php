<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

            {{-- Top nav: fixed height, ye row bata height ghataudaina --}}
            <div class="shrink-0">
                @include('layouts.navigation')
            </div>

            {{-- Sidebar + content row: remaining height matra, ra yo row afai scroll hudaina --}}
            <div class="flex flex-1 min-h-0 overflow-hidden">

                {{-- Sidebar: parent jati height, aafai bhitra scroll --}}
                @include('layouts.sidebar')

                {{-- Right side: page heading + content, yehi matra scroll hune --}}
                <div class="flex-1 min-w-0 overflow-y-auto">

                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main>
                        {{ $slot }}
                    </main>

                </div>

            </div>
        </div>
    </body>
</html>
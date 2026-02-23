<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

        <!-- Scripts -->
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            <div class="layout__admin" x-data="{openAside: true}">
                <aside class="layout__admin--aside" :class="{ active: !openAside }">
                    <button class="layout__admin--aside-button" :class="{ active: !openAside }" @click="openAside = !openAside">
                        <svg viewBox="0 0 24 24" width="24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Arrow / Caret_Left_SM"> <path id="Vector" d="M13 15L10 12L13 9" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
                    </button>
                    <x-nav-admin></x-nav-admin>
                </aside>
                <div class="layout__admin--content">
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
                    <main class="layout__admin--main">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        <script>
            window.__ACL__ = @json([
                'role' => auth()->user()?->role,
                'permissions' => auth()->check() ? \App\Support\Acl::userPermissions(auth()->user()) : [],
            ]);
            // <button v-if="can('units.move_state')">Mover unidad</button>
        </script>

    </body>
</html>

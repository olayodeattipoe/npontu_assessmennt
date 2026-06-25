<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Npontu Tasks') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                @layer base {
                    body {
                        font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system, sans-serif;
                    }
                }
            </style>
        @endif
    </head>
    <body class="antialiased bg-[#FDFDFC] text-[#1B1B18] min-h-screen flex flex-col relative selection:bg-gray-100 selection:text-black">
        
        <!-- Technical Grid Background Overlay (Fixed with pointer-events-none) -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#E3E3E0_1px,transparent_1px),linear-gradient(to_bottom,#E3E3E0_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_50%,transparent_100%)] opacity-40 pointer-events-none z-0"></div>

        <!-- Sleek Top Header Navigation -->
        @if (Route::has('login'))
            <header class="w-full max-w-7xl mx-auto flex justify-end p-6 absolute top-0 left-0 right-0 z-50">
                <nav class="flex gap-6 items-center backdrop-blur-md bg-white/60 px-5 py-2.5 rounded-full border border-[#E3E3E0] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.02)]">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-medium tracking-wide uppercase text-gray-600 hover:text-black transition duration-150">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-medium tracking-wide uppercase text-gray-600 hover:text-black transition duration-150">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs font-medium tracking-wide uppercase text-gray-600 hover:text-black transition duration-150">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            </header>
        @endif

        <!-- Centered Core Workspace -->
        <main class="flex-1 flex items-center justify-center p-6 relative z-10">
            
            <!-- Pure Minimalist Monolithic Card -->
            <div class="w-full max-w-md bg-white border border-[#E3E3E0] rounded-xl p-10 shadow-[0px_4px_24px_rgba(0,0,0,0.04)] relative">
                
                <!-- Structural Header -->
                <div class="mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 block mb-1">Platform</span>
                    <h1 class="text-2xl font-semibold tracking-tight text-[#1B1B18]">
                        Npontu Task Manager
                    </h1>
                </div>
                
                <!-- Body Copy -->
                <p class="text-sm text-gray-600 leading-relaxed font-light">
                    Streamline your engineering workflows, manage core internal deliverables, and maintain project velocity with zero friction.
                </p>

            </div>

        </main>
    </body>
</html>
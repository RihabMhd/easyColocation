<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyColoc</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white text-slate-900 font-['Inter']">

    <nav class="border-b border-slate-100 py-5">
        <div class="max-w-5xl mx-auto px-6 flex justify-between items-center">
            <div class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                <span class="text-indigo-600 w-6 h-6">
                    @svg('iconpark-family-o')
                </span>
                Easy<span class="text-indigo-600">Coloc</span>
            </div>

            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('user.colocations.index') }}"
                        class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">
                        Go to App
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                        Log in
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 pt-24 pb-32">
        <div class="max-w-3xl">
            <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1] mb-8">
                Stop arguing about <br>
                <span class="text-indigo-600">who paid for the milk.</span>
            </h1>

            <p class="text-xl text-slate-500 leading-relaxed mb-12 max-w-2xl">
                A simple dashboard to manage your colocation, track shared expenses, and keep your history organized.
                Built by students, for students.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                @auth
                    <a href="{{ route('user.colocations.index') }}"
                        class="inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition text-lg">
                        Go to Dashboard →
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition text-lg">
                        Start your coloc
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center bg-slate-50 text-slate-600 px-8 py-4 rounded-xl font-bold hover:bg-slate-100 transition text-lg">
                        Log in
                    </a>
                @endauth
            </div>
        </div>
    </main>

</body>

</html>

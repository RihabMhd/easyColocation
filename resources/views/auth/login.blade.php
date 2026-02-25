<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="text-2xl font-bold tracking-tight text-slate-900 mb-2">
            
            Easy<span class="text-indigo-600">Coloc</span>
        </div>
        <h2 class="text-xl font-semibold text-slate-600">Welcome back</h2>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 transition shadow-sm"
                placeholder="name@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 transition shadow-sm"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition duration-200">
            Log in to Dashboard
        </button>

        <p class="text-center text-sm text-slate-500 pt-2">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Register</a>
        </p>
    </form>
</x-guest-layout>

<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="text-2xl font-bold tracking-tight text-slate-900 mb-2">
            Easy<span class="text-indigo-600">Coloc</span>
        </div>
        <h2 class="text-xl font-semibold text-slate-600">Create your account</h2>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required autofocus 
                   class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 transition shadow-sm"
                   placeholder="Alex Smith">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 transition shadow-sm"
                   placeholder="alex@university.edu">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 transition shadow-sm"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 transition shadow-sm"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition duration-200">
                Create Account
            </button>
        </div>

        <p class="text-center text-sm text-slate-500 pt-2">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Log in</a>
        </p>
    </form>
</x-guest-layout>
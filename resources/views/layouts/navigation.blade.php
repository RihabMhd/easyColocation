<div x-data="{ open: false }">
    <div class="flex items-center justify-between px-4 py-2 bg-white border-b lg:hidden">
        <span class="text-xl font-bold tracking-tight text-gray-950">Laravel</span>
        <button @click="open = !open" class="p-2 text-gray-600 rounded-md hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 transform bg-white border-r border-gray-200 lg:translate-x-0">
        <div class="flex flex-col h-full">
            <div class="flex items-center h-16 px-6 border-b border-gray-100">
                <span class="text-xl font-bold tracking-tight text-gray-950">Laravel</span>
            </div>

            <nav class="flex-1 px-0 py-4 space-y-1 overflow-y-auto">
                <p class="px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Main</p>

                @php
                    $base = 'flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all w-full border-r-4';
                    $active = 'bg-indigo-50 text-indigo-700 border-indigo-600';
                    $inactive = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent';
                @endphp

                <a href="{{ route('dashboard') }}"
                    class="{{ $base }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Dashboard') }}
                </a>

                <a href="{{ route('colocations.index') }}"
                    class="{{ $base }} {{ request()->routeIs('colocations.index') ? $active : $inactive }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ __('My Colocations') }}
                </a>

                <a href="{{ route('colocations.historique') }}"
                    class="{{ $base }} {{ request()->routeIs('colocations.historique') ? $active : $inactive }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Historique') }}
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <div class="px-2 mb-4">
                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="block px-2 py-2 text-xs font-medium text-gray-600 hover:text-gray-900">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-2 py-2 text-xs font-medium text-red-600 hover:text-red-700">Log
                        Out</button>
                </form>
            </div>
        </div>
    </aside>

    <div x-show="open" @click="open = false" class="fixed inset-0 z-40 bg-gray-950/50 lg:hidden"></div>
</div>

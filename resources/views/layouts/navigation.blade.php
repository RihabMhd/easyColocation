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
                <span class="text-xl font-bold tracking-tight text-gray-950">EasyColocation</span>
            </div>

            <nav class="flex-1 px-0 py-4 space-y-1 overflow-y-auto">
                @php
                    $base = 'flex items-center gap-3 px-6 py-3 text-sm font-medium transition-all w-full border-r-4';
                    $active = 'bg-indigo-50 text-indigo-700 border-indigo-600';
                    $inactive = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent';
                @endphp

                {{-- the menu of the admin --}}
                @if (Auth::user()->role_id == 1)
                    <p class="px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Administration
                    </p>

                    <a href="{{ route('admin.statistiques') }}"
                        class="{{ $base }} {{ request()->routeIs('admin.statistiques') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        {{ __('Statistiques') }}
                    </a>

                    <a href="{{ route('admin.users') }}"
                        class="{{ $base }} {{ request()->routeIs('admin.users') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{ __('Manage Users') }}
                    </a>

                    <a href="{{ route('admin.colocations') }}"
                        class="{{ $base }} {{ request()->routeIs('admin.colocations') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ __('All Colocations') }}
                    </a>
                @endif

                {{-- the menu of the user --}}
                @if (Auth::user()->role_id == 2)
                    <p class="px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Main Menu</p>

                    <a href="{{ route('user.colocations.index') }}"
                        class="{{ $base }} {{ request()->routeIs('user.colocations.index') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __('My Colocations') }}
                    </a>

                    <a href="{{ route('user.colocations.historique') }}"
                        class="{{ $base }} {{ request()->routeIs('user.colocations.historique') ? $active : $inactive }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Historique') }}
                    </a>
                @endif
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
                        class="w-full text-left px-2 py-2 text-xs font-medium text-red-600 hover:text-red-700">
                        Log Out</button>
                </form>
            </div>
        </div>
    </aside>

    <div x-show="open" @click="open = false" class="fixed inset-0 z-40 bg-gray-950/50 lg:hidden"></div>
</div>

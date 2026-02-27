<x-guest-layout>
    <div class="max-w-md mx-auto my-12 p-8 bg-white border border-gray-200 rounded-2xl shadow-sm text-center">
        <h2 class="text-2xl font-bold text-gray-950 mb-4">You've been invited!</h2>
        <p class="mb-8 text-gray-600">You are invited to join <strong>{{ $invitation->colocation->name }}</strong>.</p>
        
        <div class="flex flex-col gap-3">
            
            <form action="{{ route('user.invitations.process', $invitation->token) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 px-4 font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 transition shadow-sm">
                    Accept and Join
                </button>
            </form>

            <form action="{{ route('user.invitations.refuse', $invitation->token) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 px-4 font-semibold text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition">
                    Refuse Invitation
                </button>
            </form>

        </div>
    </div>
</x-guest-layout>
@extends('layouts.app')

@section('content')
    <h1>{{ $colocation->name }}</h1>
    <p>{{ $colocation->description }}</p>
    <span>{{ $colocation->status }}</span>

 
    @php
        $myMembership = $colocation->memberships->where('user_id', auth()->id())->first();
    @endphp

    @if($myMembership)

       
        @if($myMembership->internal_role === 'owner')
            <form action="{{ route('colocations.transfer', $colocation->id) }}" method="POST">
                @csrf
                <label>Transfer Ownership To:</label>
                <select name="userId">
                    @foreach($colocation->memberships->where('internal_role', '!=', 'owner') as $member)
                        <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Transfer Ownership</button>
            </form>

       
        @else
            <form action="{{ route('colocations.quit', $colocation->id) }}" method="POST">
                @csrf
                <button type="submit">Quit Colocation</button>
            </form>
        @endif

    @endif

    
    @if($myMembership && $myMembership->internal_role === 'owner')
        <a href="{{ route('colocations.edit', $colocation->id) }}">Edit</a>
        <form action="{{ route('colocations.destroy', $colocation->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    @endif

@endsection
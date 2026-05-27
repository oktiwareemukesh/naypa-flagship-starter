@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-semibold tracking-tight mb-8">Themes</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($themes as $slug => $theme)
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 flex flex-col">
            <div class="flex-1">
                <div class="font-semibold text-xl">{{ $theme['name'] }}</div>
                <div class="text-xs text-zinc-500 mt-1">{{ $theme['version'] }} • {{ $theme['author'] }}</div>
                <p class="text-sm text-zinc-400 mt-4">{{ $theme['description'] }}</p>
            </div>

            <div class="mt-8">
                @if($theme['active'])
                    <span class="inline-block px-4 py-1.5 text-xs rounded-2xl bg-emerald-500/10 text-emerald-400">Currently Active</span>
                @else
                    <form action="{{ route('admin.themes.activate', $slug) }}" method="POST">
                        @csrf
                        <button class="w-full py-3 rounded-3xl bg-white text-zinc-900 font-semibold text-sm hover:bg-zinc-100 transition">Activate Theme</button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
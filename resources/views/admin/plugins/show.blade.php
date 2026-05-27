@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 text-zinc-200">
    <a href="{{ route('admin.plugins.index') }}" class="text-sm text-emerald-400 hover:text-emerald-300 flex items-center gap-x-1 mb-6">← Back to Plugins</a>

    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">{{ $plugin['name'] }}</h1>
                <div class="text-zinc-400 mt-1">{{ $plugin['description'] }}</div>
            </div>
            <div class="text-right">
                <div class="font-mono text-sm text-zinc-500">{{ $plugin['version'] }}</div>
                <div class="text-xs text-zinc-400">{{ $plugin['author'] }}</div>
            </div>
        </div>

        <div class="my-8 border-t border-zinc-800"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
            <div>
                <div class="text-zinc-400 mb-2">Status</div>
                @if($dbPlugin && $dbPlugin->enabled)
                    <span class="px-4 py-1.5 rounded-2xl bg-emerald-500/10 text-emerald-400 text-xs font-medium">ACTIVE</span>
                @else
                    <span class="px-4 py-1.5 rounded-2xl bg-zinc-700 text-zinc-400 text-xs font-medium">INACTIVE</span>
                @endif
            </div>
            <div>
                <div class="text-zinc-400 mb-2">Hooks Provided</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($plugin['manifest']['hooks'] ?? [] as $hook)
                        <span class="px-3 py-1 bg-zinc-800 rounded-2xl text-xs font-mono">{{ $hook }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-10 flex gap-x-4">
            @if($dbPlugin && $dbPlugin->enabled)
                <form action="{{ route('admin.plugins.deactivate', $plugin['slug']) }}" method="POST">
                    @csrf
                    <button class="px-6 py-3 rounded-3xl border border-rose-500/40 text-rose-400 hover:bg-rose-950 transition">Deactivate Plugin</button>
                </form>
            @else
                <form action="{{ route('admin.plugins.activate', $plugin['slug']) }}" method="POST">
                    @csrf
                    <button class="px-8 py-3 rounded-3xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold transition">Activate Plugin</button>
                </form>
            @endif

            <form action="{{ route('admin.plugins.destroy', $plugin['slug']) }}" method="POST" onsubmit="return confirm('Delete this plugin permanently?')">
                @csrf
                @method('DELETE')
                <button class="px-6 py-3 rounded-3xl border border-zinc-700 hover:bg-zinc-800 text-zinc-400 transition">Delete Plugin</button>
            </form>
        </div>
    </div>
</div>
@endsection
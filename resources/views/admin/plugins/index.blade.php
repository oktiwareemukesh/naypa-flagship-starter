@extends('layouts.admin')

@section('content
') 
<div class="min-h-screen bg-zinc-950 text-zinc-200">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Plugins</h1>
                <p class="text-zinc-400 mt-1">Manage third-party extensions for your Naypa platform</p>
            </div>
            
            <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-x-3">
                @csrf
                <label class="cursor-pointer inline-flex items-center gap-x-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-3xl text-sm font-semibold transition">
                    <input type="file" name="plugin_zip" class="hidden" accept=".zip" onchange="this.form.submit()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v-4m0 0l4-4m-4 4l4 4m12-4v4m0 0l-4 4m4-4l-4-4" />
                    </svg>
                    Upload Plugin ZIP
                </label>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-3xl">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-3xl">{{ session('error') }}</div>
        @endif

        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="text-zinc-400 border-b border-zinc-800">
                    <tr>
                        <th class="text-left px-6 py-4 font-normal">Plugin</th>
                        <th class="text-left px-6 py-4 font-normal">Version</th>
                        <th class="text-left px-6 py-4 font-normal">Author</th>
                        <th class="text-left px-6 py-4 font-normal">Status</th>
                        <th class="text-right px-6 py-4 font-normal">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($discovered as $slug => $plugin)
                    @php $isActive = isset($activePlugins[$slug]); @endphp
                    <tr class="hover:bg-zinc-950/60 transition">
                        <td class="px-6 py-5">
                            <div class="font-medium">{{ $plugin['name'] }}</div>
                            <div class="text-xs text-zinc-500 mt-0.5">{{ Str::limit($plugin['description'] ?? '', 80) }}</div>
                        </td>
                        <td class="px-6 py-5 text-zinc-400 font-mono text-xs">{{ $plugin['version'] }}</td>
                        <td class="px-6 py-5 text-zinc-400">{{ $plugin['author'] ?? '—' }}</td>
                        <td class="px-6 py-5">
                            @if($isActive)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400">Active</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-zinc-700 text-zinc-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right space-x-2">
                            <a href="{{ route('admin.plugins.show', $slug) }}" class="text-xs px-4 py-2 rounded-2xl border border-zinc-700 hover:bg-zinc-800 transition">Details</a>
                            
                            @if($isActive)
                                <form action="{{ route('admin.plugins.deactivate', $slug) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-4 py-2 rounded-2xl border border-rose-500/40 text-rose-400 hover:bg-rose-950 transition">Deactivate</button>
                                </form>
                            @else
                                <form action="{{ route('admin.plugins.activate', $slug) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white transition">Activate</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                            No plugins found. Upload your first plugin ZIP above.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
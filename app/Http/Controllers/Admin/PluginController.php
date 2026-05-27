<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Services\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class PluginController extends Controller
{
    protected PluginManager $pluginManager;

    public function __construct(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    public function index()
    {
        $discovered = $this->pluginManager->getDiscovered();
        $activePlugins = Plugin::active()->get()->keyBy('slug');

        return view('admin.plugins.index', [
            'discovered' => $discovered,
            'activePlugins' => $activePlugins,
        ]);
    }

    public function show(string $slug)
    {
        $plugin = $this->pluginManager->getDiscovered()[$slug] ?? null;
        
        if (!$plugin) {
            abort(404);
        }

        $dbPlugin = Plugin::where('slug', $slug)->first();

        return view('admin.plugins.show', [
            'plugin' => $plugin,
            'dbPlugin' => $dbPlugin,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip|max:51200',
        ]);

        $zipFile = $request->file('plugin_zip');
        $tempPath = $zipFile->store('temp-plugins', 'local');
        $fullTempPath = storage_path('app/' . $tempPath);

        $zip = new ZipArchive();
        if ($zip->open($fullTempPath) !== true) {
            Storage::disk('local')->delete($tempPath);
            return back()->with('error', 'Invalid ZIP file.');
        }

        $extractPath = storage_path('app/temp-plugins/extracted-' . Str::random(8));
        $zip->extractTo($extractPath);
        $zip->close();

        $manifestPath = $extractPath . '/plugin.json';
        if (!File::exists($manifestPath)) {
            File::deleteDirectory($extractPath);
            Storage::disk('local')->delete($tempPath);
            return back()->with('error', 'plugin.json manifest not found in ZIP root.');
        }

        $manifest = json_decode(File::get($manifestPath), true);
        $slug = $manifest['slug'] ?? Str::slug($manifest['name'] ?? 'unknown-plugin');

        $targetPath = base_path('plugins/' . $slug);
        
        if (File::exists($targetPath)) {
            File::deleteDirectory($extractPath);
            Storage::disk('local')->delete($tempPath);
            return back()->with('error', 'A plugin with this slug already exists.');
        }

        File::moveDirectory($extractPath, $targetPath);
        Storage::disk('local')->delete($tempPath);

        $this->pluginManager->discover();

        return redirect()->route('admin.plugins.show', $slug)
            ->with('success', "Plugin '{$manifest['name']}' uploaded successfully. You can now activate it.");
    }

    public function activate(string $slug)
    {
        $result = $this->pluginManager->activate($slug);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function deactivate(string $slug)
    {
        $result = $this->pluginManager->deactivate($slug);

        return back()->with('success', $result['message']);
    }

    public function destroy(string $slug)
    {
        $pluginPath = base_path('plugins/' . $slug);

        if (File::exists($pluginPath)) {
            File::deleteDirectory($pluginPath);
        }

        Plugin::where('slug', $slug)->delete();

        $this->pluginManager->discover();

        return redirect()->route('admin.plugins.index')
            ->with('success', 'Plugin deleted successfully.');
    }
}
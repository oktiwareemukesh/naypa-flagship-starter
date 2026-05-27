<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ThemeController extends Controller
{
    protected ThemeManager $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    public function index()
    {
        $themes = $this->themeManager->getAllThemes();
        return view('admin.themes.index', compact('themes'));
    }

    public function activate(string $slug)
    {
        $success = $this->themeManager->setActive($slug);

        if ($success) {
            return back()->with('success', 'Theme activated successfully.');
        }

        return back()->with('error', 'Failed to activate theme.');
    }
}
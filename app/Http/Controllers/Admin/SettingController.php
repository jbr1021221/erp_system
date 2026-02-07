<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:setting-list|setting-edit', only: ['index', 'update']),
        ];
    }

    public function index()
    {
        $settings = GeneralSetting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token', '_method');

        foreach ($settings as $key => $value) {
            GeneralSetting::where('key', $key)->update(['value' => $value]);
        }

        // Clear settings cache to apply changes immediately
        GeneralSetting::clearCache();

        return redirect()->back()->with('success', 'Settings updated successfully. Please refresh the page to see font size changes.');
    }
}

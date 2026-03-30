<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(SettingsService $settings)
    {
        return view('admin.settings', [
            'settings' => $settings->get('*') ?? []
        ]);
    }

    public function update(Request $request, SettingsService $settings)
    {
        foreach ($request->except('_token') as $key => $value) {
            $settings->set($key, $value);
        }

        return back()->with('success', 'Settings updated');
    }
}
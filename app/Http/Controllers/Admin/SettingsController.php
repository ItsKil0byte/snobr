<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SettingsController extends Controller
{
    public function edit(SettingsService $settings)
    {
        $this->authorize('settings.view');

        return view('admin.settings', [
            'settingsData' => Arr::dot($settings->all())
        ]);
    }

    public function update(Request $request, SettingsService $settings)
    {
        $this->authorize('settings.update');

        $data = [];
        foreach ($request->except('_token') as $key => $value) {
            $dotKey = str_replace('_', '.', $key);
            if (str_ends_with($dotKey, '.enabled')) {
            $value = (bool)$value;
            }
            $data[$dotKey] = $value;
        }

        $settings->setMany($data);

        return back()->with('success', 'Settings updated');
    }
}
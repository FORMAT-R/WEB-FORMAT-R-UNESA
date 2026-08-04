<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'logo']);

        // Only superadmin can update general and system settings
        if (auth()->user()->role !== 'superadmin') {
            $data = $request->only(['contactEmail', 'contactPhone', 'instagram', 'youtube', 'address']);
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Handle image uploads if superadmin
        if (auth()->user()->role === 'superadmin') {
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('settings', 'public');
                Setting::updateOrCreate(['key' => 'siteLogo'], ['value' => $path]);
            }
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}

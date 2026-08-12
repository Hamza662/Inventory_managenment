<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\Currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::current();
        $currencies = Currencies::all();

        return view('admin.settings.edit', compact('setting', 'currencies'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:120',
            'store_tagline' => 'nullable|string|max:255',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|string|max:40',
            'store_address' => 'nullable|string|max:500',
            'currency_code' => ['required', 'string', Rule::in(Currencies::codes())],
            'footer_text' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg,webp,svg|max:1024',
        ]);

        $data['currency_symbol'] = Currencies::symbol($data['currency_code']);

        $setting = Setting::current();
        $uploadPath = public_path('uploads/settings');

        if (! File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        if ($request->hasFile('logo')) {
            $this->deletePublicFile($setting->logo);
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move($uploadPath, $logoName);
            $data['logo'] = 'uploads/settings/' . $logoName;
        } else {
            unset($data['logo']);
        }

        if ($request->hasFile('favicon')) {
            $this->deletePublicFile($setting->favicon);
            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move($uploadPath, $faviconName);
            $data['favicon'] = 'uploads/settings/' . $faviconName;
        } else {
            unset($data['favicon']);
        }

        $setting->update($data);
        Setting::clearCache();

        return back()->with('success', 'Settings updated successfully.');
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

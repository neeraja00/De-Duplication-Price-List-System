<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeduplicationSetting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = DeduplicationSetting::first();
        if (!$settings) {
            $settings = DeduplicationSetting::create([
                'ignore_hyphens' => true,
                'ignore_spaces' => true,
                'ignore_special_characters' => true,
                'ignore_leading_zeros' => false,
                'fuzzy_match_threshold' => 85,
            ]);
        }
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $request->validate([
            'ignore_hyphens' => 'boolean',
            'ignore_spaces' => 'boolean',
            'ignore_special_characters' => 'boolean',
            'ignore_leading_zeros' => 'boolean',
            'fuzzy_match_threshold' => 'integer|min:50|max:100',
        ]);

        $settings = DeduplicationSetting::first();
        $settings->update($request->all());

        \App\Services\AuditLogger::log('settings_updated', 'settings', 'success', 'Deduplication settings updated', [
            'ignore_hyphens' => $settings->ignore_hyphens,
            'ignore_spaces' => $settings->ignore_spaces,
            'ignore_special_characters' => $settings->ignore_special_characters,
            'ignore_leading_zeros' => $settings->ignore_leading_zeros,
            'fuzzy_match_threshold' => $settings->fuzzy_match_threshold
        ]);

        return response()->json(['message' => 'Settings updated successfully', 'settings' => $settings]);
    }
}

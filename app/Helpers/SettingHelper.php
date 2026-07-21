<?php

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        // Cache could be implemented here for better performance
        $setting = \App\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

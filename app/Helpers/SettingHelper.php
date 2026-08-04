<?php

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        if (in_array($key, ['cabinetName', 'cabinetLogo', 'cabinetVision', 'cabinetMission'])) {
            $c = get_active_cabinet();
            if ($c) {
                if ($key == 'cabinetName') return $c->name;
                if ($key == 'cabinetLogo') return $c->logo;
                if ($key == 'cabinetVision') return $c->vision;
                if ($key == 'cabinetMission') return $c->mission;
            }
        }
        // Cache could be implemented here for better performance
        $setting = \App\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('get_active_cabinet')) {
    function get_active_cabinet()
    {
        return \App\Models\Cabinet::where('is_active', true)->first();
    }
}

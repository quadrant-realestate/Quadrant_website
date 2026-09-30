<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        $setting = \Illuminate\Support\Facades\DB::table('settings')
                    ->where('key', $key)
                    ->first();

        return $setting ? $setting->value : $default;
    }
}
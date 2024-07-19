<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getStartTime()
    {
        $settings = Setting::where('group', 'start-time') -> get();
        $start_times = [];
        foreach ($settings as $setting) {
            $start_times[$setting->name] = $setting->value;
        }
        return $start_times;
    }
}

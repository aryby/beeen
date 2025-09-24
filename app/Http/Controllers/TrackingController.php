<?php

namespace App\Http\Controllers;

use App\Models\EmailTracking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function open(string $token)
    {
        $tracking = EmailTracking::where('token', $token)->first();
        if ($tracking) {
            $tracking->open_count = $tracking->open_count + 1;
            if (!$tracking->first_open_at) {
                $tracking->first_open_at = now();
            }
            $tracking->last_open_at = now();
            $tracking->save();
        }

        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5f0i8AAAAASUVORK5CYII=');
        return response($pixel, 200)->header('Content-Type', 'image/png');
    }

    public function click(Request $request, string $token)
    {
        $url = $request->query('url');
        $tracking = EmailTracking::where('token', $token)->first();
        if ($tracking) {
            $tracking->click_count = $tracking->click_count + 1;
            $tracking->last_clicked_at = now();
            $tracking->save();
        }

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            return redirect()->away($url);
        }
        return redirect()->to('/');
    }
}



<?php

namespace DavidNeal\LaravelSesTracker\Controllers;

use DavidNeal\LaravelSesTracker\Models\EmailOpen;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OpenController extends BaseController
{
    public function open($beaconIdentifier)
    {
        try {
            $open = EmailOpen::whereBeaconIdentifier($beaconIdentifier)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'errors' => ['Invalid Beacon']], 422);
        }

        $open->opened_at = now();
        $open->save();

        return redirect(config('app.url'). '/laravel-ses/to.png');
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationRequest;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NavigationController extends Controller
{
    /**
     * @param NavigationRequest $request
     * @return JsonResponse
     * @throws ErrorException
     */
    public function routing(NavigationRequest $request): JsonResponse
    {
        ['lat' => $originLat, 'lng' => $originLng] = $request->input('origin');
        ['lat' => $destinationLat, 'lng' => $destinationLng] = $request->input('destination');
        $response = Http::withHeaders([
            'Api-Key' => config('map.api_key')
        ])->get('https://api.neshan.org/v4/direction', [
            'type' => 'car',
            'origin' => "{$originLat}, {$originLng}",
            'destination' => "{$destinationLat}, {$destinationLng}"
        ]);

        if (!$response->successful()) {
            Log::info($response->json());
            throw new ErrorException("Api Error", 500);
        }

        $polyline = $response->collect()->get('routes')[0]['overview_polyline']['points'];

        return $this->json(200, null, [
            'polyline' => $polyline
        ]);
    }
}

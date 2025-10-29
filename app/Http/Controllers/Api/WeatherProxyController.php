<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherProxyController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $city = $request->query('city', 'Surabaya');
        $apiKey = config('services.openweather.api_key');
        $baseUrl = rtrim(config('services.openweather.base_url', 'https://api.openweathermap.org/data/2.5'), '/');

        if (! $apiKey) {
            return response()->json([
                'message' => 'No API key configured. Set OPENWEATHER_API_KEY in your .env file before calling this endpoint in production.',
                'meta' => [
                    'city' => $city,
                    'provider' => 'openweathermap.org',
                    'instructions' => 'Register for a free account on https://openweathermap.org, copy the API key, then add OPENWEATHER_API_KEY=your-key to the .env file.',
                ],
                'sample_request' => route('api.demo.weather', ['city' => $city]),
            ]);
        }

        $response = Http::get("{$baseUrl}/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to fetch weather data from OpenWeatherMap.',
                'meta' => [
                    'city' => $city,
                    'status' => $response->status(),
                ],
                'error' => $response->json(),
            ], $response->status() ?: 500);
        }

        return response()->json([
            'message' => 'Weather data fetched from OpenWeatherMap successfully.',
            'meta' => [
                'city' => $city,
                'provider' => 'openweathermap.org',
            ],
            'data' => $response->json(),
        ]);
    }
}

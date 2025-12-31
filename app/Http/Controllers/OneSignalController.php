<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OneSignalPlayer;
use Illuminate\Support\Facades\Http;

class OneSignalController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'player_id' => 'required|string',
            'platform' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);

        $player = OneSignalPlayer::updateOrCreate(
            ['player_id' => $data['player_id']],
            [
                'user_id' => $request->user()?->id,
                'platform' => $data['platform'] ?? null,
                'metadata' => $data['metadata'] ?? null,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function sendTest(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $players = $user->onesignalPlayers()->pluck('player_id')->all();
        if (empty($players)) {
            return response()->json(['error' => 'No OneSignal players found for this user'], 400);
        }

        $appId = env('ONESIGNAL_APP_ID');
        $restKey = env('ONESIGNAL_REST_API_KEY');

        if (!$appId || !$restKey) {
            return response()->json(['error' => 'OneSignal credentials not configured'], 500);
        }

        $body = [
            'app_id' => $appId,
            'include_player_ids' => $players,
            'headings' => ['en' => 'Test Notification'],
            'contents' => ['en' => 'This is a test notification from the app.'],
            'url' => url('/'),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $restKey,
            'Accept' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', $body);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to send notification', 'details' => $response->body()], 500);
        }

        return response()->json(['success' => true, 'response' => $response->json()]);
    }
}

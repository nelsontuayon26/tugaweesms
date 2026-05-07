<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    /**
     * Store a new push subscription.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'subscription.endpoint' => 'required|url',
            'subscription.keys.auth' => 'nullable|string',
            'subscription.keys.p256dh' => 'nullable|string',
            'user_agent' => 'nullable|string',
            'platform' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $subscription = $request->input('subscription');
        
        try {
            $pushSubscription = $user->updatePushSubscription(
                $subscription['endpoint'],
                $subscription['keys']['p256dh'] ?? null,
                $subscription['keys']['auth'] ?? null,
                'aesgcm'
            );

            return response()->json([
                'success' => true,
                'message' => 'Push subscription saved successfully',
                'subscription_id' => $pushSubscription->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a push subscription.
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|url'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        try {
            $user->deletePushSubscription($request->endpoint);

            return response()->json([
                'success' => true,
                'message' => 'Push subscription removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all subscriptions for the current user.
     */
    public function getSubscriptions()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $subscriptions = $user->pushSubscriptions()
            ->select('id', 'endpoint', 'created_at', 'updated_at')
            ->get()
            ->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'endpoint' => substr($sub->endpoint, 0, 50) . '...',
                    'created_at' => $sub->created_at,
                    'updated_at' => $sub->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * Test push notification for current user.
     */
    public function test(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        try {
            $user->notify(new \App\Notifications\TestPushNotification());

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

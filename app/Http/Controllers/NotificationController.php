<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(AppNotification $notification): \Illuminate\Http\JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function markAllRead(): \Illuminate\Http\JsonResponse
    {
        AppNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}

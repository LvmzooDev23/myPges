<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformNotificationResource;
use App\Models\PlatformNotification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notifications
    ) {}

    public function index(): JsonResponse
    {
        $items = auth('api')->user()->platformNotifications()->orderByDesc('created_at')->limit(50)->get();

        return response()->json(PlatformNotificationResource::collection($items));
    }

    public function markRead(int $id): JsonResponse
    {
        $notification = PlatformNotification::query()
            ->where('id', $id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $this->notifications->markRead($notification);

        return response()->json(['message' => 'Lu.']);
    }
}

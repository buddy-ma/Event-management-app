<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participation;
use App\Events\UserJoinedEvent;
use App\Notifications\NewParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ParticipationController extends Controller
{
    public function join(Event $event): JsonResponse
    {
        // Check if event is full
        if (!$event->hasAvailableSpots()) {
            throw ValidationException::withMessages([
                'event' => ['This event is already full.'],
            ]);
        }

        // Check if user is already participating
        $existingParticipation = Participation::where([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
        ])->first();

        if ($existingParticipation) {
            if ($existingParticipation->status === 'cancelled') {
                $existingParticipation->update(['status' => 'pending']);
            } else {
                throw ValidationException::withMessages([
                    'event' => ['You are already participating in this event.'],
                ]);
            }
        } else {
            $participation = Participation::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'status' => 'pending',
            ]);
        }

        // Dispatch event for real-time updates
        UserJoinedEvent::dispatch($event, auth()->user());

        // Notify event host
        $event->host->notify(new NewParticipant($event, auth()->user()));

        return response()->json([
            'message' => 'Successfully joined the event',
            'data' => $existingParticipation ?? $participation
        ], 201);
    }

    public function leave(Event $event): JsonResponse
    {
        $participation = Participation::where([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
        ])->first();

        if (!$participation) {
            throw ValidationException::withMessages([
                'event' => ['You are not participating in this event.'],
            ]);
        }

        $participation->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Successfully left the event'
        ]);
    }

    public function userParticipations(Request $request): JsonResponse
    {
        $participations = Participation::with('event')
            ->where('user_id', auth()->id())
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $participations,
            'meta' => [
                'total' => $participations->total(),
                'page' => $participations->currentPage(),
                'last_page' => $participations->lastPage()
            ]
        ]);
    }

    public function updateStatus(Event $event, Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,declined'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        // Ensure the authenticated user is the event host
        if (!$event->isHost(auth()->user())) {
            return response()->json([
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $participation = Participation::where([
            'event_id' => $event->id,
            'user_id' => $request->user_id,
        ])->firstOrFail();

        $participation->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Participation status updated successfully',
            'data' => $participation
        ]);
    }
}
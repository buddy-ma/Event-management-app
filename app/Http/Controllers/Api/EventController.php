<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Events\EventCreated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EventResource;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $events = Event::query()
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->with(['host', 'participants'])
            ->published()
            ->upcoming()
            ->paginate(10);

        return response()->json([
            'data' => EventResource::collection($events),
            'meta' => [
                'total' => $events->total(),
                'page' => $events->currentPage(),
                'last_page' => $events->lastPage()
            ]
        ]);
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = Event::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        EventCreated::dispatch($event);

        return response()->json([
            'message' => 'Event created successfully',
            'data' => new EventResource($event->load('host'))
        ], 201);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json([
            'data' => new EventResource($event->load(['host', 'participants']))
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        $event->update($request->validated());

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => new EventResource($event->load('host'))
        ]);
    }

    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }
}
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
    public function userEvents(Request $request): JsonResponse
    {
        $events = $request->user()->events()->with(['host', 'participants'])->get();
        return response()->json([
            'data' => EventResource::collection($events)
        ]);
    }

    public function topEvents(): JsonResponse
    {
        $events = Event::query()
            ->with(['host', 'participants'])
            ->published()
            ->upcoming()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'image' => $event->image,
                    'price' => $event->price,
                    'is_online' => $event->is_online,
                    'online_url' => $event->online_url,
                    'participants' => $event->participants->count(),
                    'start_date' => $event->start_date->format('Y-m-d'),
                    'address' => $event->address,
                    'category' => $event->category,
                    'user_id' => $event->user_id,
                ];
            });

        return response()->json([
            'data' => $events
        ]);
    }

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
        $event->update($request->validated());

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => new EventResource($event->load('host'))
        ]);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }
}
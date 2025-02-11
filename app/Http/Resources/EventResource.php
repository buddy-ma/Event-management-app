<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'address' => $this->address,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'max_participants' => $this->max_participants,
            'is_published' => $this->is_published,
            'price' => $this->price,
            'tags' => $this->tags,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_online' => $this->is_online,
            'online_url' => $this->online_url,
            // Related resources
            'host' => new UserResource($this->whenLoaded('host')),
            'participants' => UserResource::collection($this->whenLoaded('participants')),

            // Computed properties
            'participants_count' => $this->participants_count ?? $this->participants->count(),
            'is_full' => $this->is_full,
            'can_join' => $this->when(auth()->check(), function () {
                return !$this->participants->contains(auth()->id()) && !$this->is_full;
            }),
        ];
    }
}

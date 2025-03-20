<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'slug' => $this->slug,
            'content' => $this->content,
            'is_featured' => $this->featured,
            'author' => [
                'name' => $this->user->name,
                'slug' => $this->user->slug
            ],
            'created_date' => $this->created_at->format('M d, Y'), 
            'reading_time' => $this->reading_time,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}

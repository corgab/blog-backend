<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image,
            'slug' => $this->slug,
            'created_date' => $this->created_at->translatedFormat('M d, Y'),
            'posts_count' => $this->when(isset($this->posts_count), $this->posts_count),
            'posts' => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}

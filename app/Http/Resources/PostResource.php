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
            'image' => $this->image,
            'description' => $this->description,
            'content' => $this->content,
            'is_featured' => $this->featured,
            'created_date' => $this->created_at->format('M d, Y'), 
            'reading_time' => $this->reading_time,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            
            // SEO
            'seo' => [
                'meta_title' => $this->title,
                'meta_description' => $this->meta_description ?? substr(strip_tags($this->description), 0, 160),
                'canonical_url' => config('app.frontend_url') . $this->slug,
                'og_image' => $this->image,
                'og_type' => 'article'
            ]
        ];
    }
}

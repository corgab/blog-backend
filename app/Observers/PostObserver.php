<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->clearCache($post);

    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $this->clearCache($post);

    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->clearCache($post);

    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        $this->clearCache($post);

    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        $this->clearCache($post);
    }

    protected function clearCache($post)
    {
        // Cache singolo post
        Cache::forget("posts.show.{$post->id}");

        // Cache index
        Cache::forget('posts.index.admin');
        Cache::forget("posts.index.user.{$post->user_id}");

        // Cache cestino
        Cache::forget('posts.trash.admin');
        Cache::forget("posts.trash.user.{$post->user_id}");

    }
}

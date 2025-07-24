<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PublishPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Manda in publish il post
        $this->post->update(['status' => 'published']);
        Log::channel('publish_post')->info('Post mandato in pubblicazione', ['post_id' => $this->post->id]);

        Cache::forget("posts.show.{$this->post->id}");
        Cache::forget('posts.index.admin');
        Cache::forget("posts.index.user.{$this->post->user_id}");

        // Integrazione con i discord tramite webhook
        if(app()->environment('production')) {
            SendDiscordWebhookJob::dispatch($this->post);
            Log::channel('publish_post')->info('Post mandato su discord tramite webhook', ['post_id' => $this->post->id]);
        }

    }
}

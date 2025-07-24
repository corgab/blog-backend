<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendDiscordWebhookJob implements ShouldQueue
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
        $webhookUrl = config('services.discord.webhook_url');

        $url = config('app.frontend_url');
        $postUrl = $url . '/' . $this->post->slug;
        $contentPlain = strip_tags($this->post->content); // Rimuove HTML
        $content = Str::limit($contentPlain, 500);

        $message = <<<DISCORD
        📢 **Nuovo post pubblicato!**

        **Titolo:** {$this->post->title}

        {$content}

        **Continua a leggere qui:** {$postUrl}
        **Data:** {$this->post->created_at->format('d/m/Y H:i')}

        🚀 Leggilo ora su https://www.craftedhub.it/
        @here
        DISCORD;

        Http::post($webhookUrl, [
            'content' => $message,
        ]);
        Log::channel('publish_post')->info('Post mandato su discord', ['post_id' => $this->post->id]);
    }


}

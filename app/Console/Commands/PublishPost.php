<?php

namespace App\Console\Commands;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Manda in pubblicazione l'ultimo post approvato";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Recupero il primo post con status "approved" ordinato dalla data di creazione più vecchia
        $post = Post::where('status', 'approved')
            ->orderBy('created_at')
            ->first();

        if($post){
            PublishPostJob::dispatch($post);
            Log::channel('publish_post')->info('Post mandato in dispatch', ['post_id' => $post->id,]);
        }
    }
}

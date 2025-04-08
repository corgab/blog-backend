<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\NewsletterMail;
use App\Models\Post;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Collection;

class SendNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 2;
    public $timeout = 60;
    public $backoff = [30, 60, 120];

    public $subscribers;
    public $posts;

    public function __construct(Collection $subscribers, Collection $posts)
    {
        $this->subscribers = $subscribers;
        $this->posts = $posts;
    }

    public function handle()
    {

        // Log::error($subscribers);
        foreach ($this->subscribers as $index => $subscriber) {
            try {
                Log::info("Invio mail a: {$subscriber->email}");

                Mail::to($subscriber->email)
                    ->send(new NewsletterMail($this->posts, $subscriber->name));

                Log::info("Mail inviata a: {$subscriber->email}");
            } catch (Throwable $e) {
                Log::error("Impossibile inviare la newsletter a {$subscriber->email}: " . $e->getMessage());
            }
        }
    }
}

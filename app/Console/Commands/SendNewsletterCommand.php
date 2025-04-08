<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Newsletter;
use App\Jobs\SendNewsletter;
use App\Models\Post;


class SendNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manda la newsletter a tutti gli iscritti';

    /**
     * Execute the console command.
     */
    // app/Console/Commands/SendNewsletterCommand.php

    public function handle()
    {
        $subscribers = Newsletter::all();
        $posts = Post::where('status', 'published')->latest()->take(3)->get();
        SendNewsletter::dispatch($subscribers, $posts);
        
        $this->info("Newsletter inviata gli iscritti");
    }

}

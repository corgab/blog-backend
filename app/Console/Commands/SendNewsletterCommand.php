<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Newsletter;
use App\Jobs\SendNewsletter;


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
        SendNewsletter::dispatch($subscribers);
        $this->info('Newsletter inviata con successo.');
    }

}

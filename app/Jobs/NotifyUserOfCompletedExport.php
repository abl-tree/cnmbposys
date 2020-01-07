<?php

namespace App\Jobs;

use App\User;
use App\Notifications\SvaExportReady;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public $user;

    public $filename;

    public $realFilename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $filename, $realFilename)
    {   
        $this->user = $user;

        $this->filename = $filename;

        $this->realFilename = $realFilename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new SVAExportReady($this->filename, $this->realFilename));
    }
}

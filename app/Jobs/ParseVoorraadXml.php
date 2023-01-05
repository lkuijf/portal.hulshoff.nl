<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Helpers\XmlParse;
use App\Models\Job;

class ParseVoorraadXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $startedAt = date("Y-m-d H:i:s");
        $job = new Job;
        $job->save();
        $results = XmlParse::parseIt('vrdstand', $job->id);
        $endedAt = date("Y-m-d H:i:s");

        $job->updateEntry(get_class($this), $startedAt, $endedAt, $results);
    }
}

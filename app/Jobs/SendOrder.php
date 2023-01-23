<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LogSendOrder;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class SendOrder implements ShouldQueue
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
        
        try {

            $startedAt = date("Y-m-d H:i:s");
            $job = new Job;
            $job->save();

            $totalFiles = count(Storage::disk('local_xml_order_out')->files());
            $totalFilesProcessed = 0;
            $totalFilesSkipped = 0;
            $x = 1;
            foreach(Storage::disk('local_xml_order_out')->files() as $file) {
echo "\r" . $x++ . '/' . $totalFiles;
                $foundRec = LogSendOrder::where('file', $file)->first();
                if($foundRec) {
                    $totalFilesSkipped++;
                } else {
                    $totalFilesProcessed++;
                    $sendOrder = new LogSendOrder;
                    $sendOrder->file = $file;
                    $sendOrder->job_id = $job->id;
                    
                    $xmlString = Storage::disk('local_xml_order_out')->get($file);

                    // Http::fake([
                    //     'https://edi.hulshoff.nl/api/accept/interface-web/BerichtOrders' => Http::sequence()->pushStatus(404)
                    // ]);

                    $response = Http::withHeaders([
                        'Content-Type' => 'application/xml'
                    ])->post('https://edi.hulshoff.nl/api/accept/interface-web/BerichtOrders', [
                        'body' => $xmlString,
                    ]);

                    if($response->failed()) {
                        $err = $response->status();
                        if($response->serverError()) $err .= ' serverError';
                        if($response->clientError()) $err .= ' clientError';
                        $sendOrder->errors = $err;
                    }

                    $sendOrder->save();

                }
            }
            $results = [
                'total' => $totalFiles,
                'processed' => $totalFilesProcessed,
                'skipped' => $totalFilesSkipped,
            ];
            $endedAt = date("Y-m-d H:i:s");
            $job->updateEntry(get_class($this), $startedAt, $endedAt, $results);
        
        } catch (\Exception $e) {
            Mail::raw($e->getMessage(), function ($message) {
                $message
                ->to('leon@wtmedia-events.nl')
                ->subject('SendOrder job failed!');
            });
            throw $e; //rethrow
        }

    }
}

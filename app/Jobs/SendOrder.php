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

                    // $response = Http::withHeaders([
                    //     'Content-Type' => 'application/xml'
                    // ])->post('https://edi.hulshoff.nl/api/accept/interface-wti/BerichtOrders', [
                    //     'body' => $xmlString,
                    // ]);
// echo $xmlString;

                    // $response = Http::withBody($xmlString, 'application/xml')->post('https://edi.hulshoff.nl/api/accept/interface-wti/BerichtOrders');
                    

echo "\n\n";
echo '<?xml version="1.0"?>
<bericht><bericht-type>IFC-ORDER-IN</bericht-type><bericht-id>1675769785</bericht-id><orders><order><ord-klant-code>55054</ord-klant-code><ord-order-code-klant>16044</ord-order-code-klant><ord-order-code-aflever>2507501</ord-order-code-aflever><ord-eta-afleveren-datum>20230218</ord-eta-afleveren-datum><ord-eta-afleveren-tijd>0000</ord-eta-afleveren-tijd><adressen><adres><afa-afleveradres-code>ALGEMEEN</afa-afleveradres-code><afa-naam>Johan Dokter</afa-naam><afa-straat>Spaklerweg 4</afa-straat><afa-huisnummer/><afa-postcode/><afa-plaats/><afa-land-code>NL</afa-land-code><afa-contactpersoon>Johan Dokter</afa-contactpersoon><afa-telefoon>0205243767</afa-telefoon><afa-e-mailadres>j.a.dokter@dnb.nl</afa-e-mailadres></adres></adressen><details><detail><odt-klant-regel-code>16044</odt-klant-regel-code><odt-artikel-code>00011</odt-artikel-code><odt-stuks-besteld>1</odt-stuks-besteld></detail></details></order></orders></bericht>';
echo "\n\n";
echo $xmlString;
echo "\n\n";
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://edi.hulshoff.nl/api/accept/interface-wti/BerichtOrders',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'<?xml version="1.0"?>
                    <bericht><bericht-type>IFC-ORDER-IN</bericht-type><bericht-id>1675769785</bericht-id><orders><order><ord-klant-code>55054</ord-klant-code><ord-order-code-klant>16044</ord-order-code-klant><ord-order-code-aflever>2507501</ord-order-code-aflever><ord-eta-afleveren-datum>20230218</ord-eta-afleveren-datum><ord-eta-afleveren-tijd>0000</ord-eta-afleveren-tijd><adressen><adres><afa-afleveradres-code>ALGEMEEN</afa-afleveradres-code><afa-naam>Johan Dokter</afa-naam><afa-straat>Spaklerweg 4</afa-straat><afa-huisnummer/><afa-postcode/><afa-plaats/><afa-land-code>NL</afa-land-code><afa-contactpersoon>Johan Dokter</afa-contactpersoon><afa-telefoon>0205243767</afa-telefoon><afa-e-mailadres>j.a.dokter@dnb.nl</afa-e-mailadres></adres></adressen><details><detail><odt-klant-regel-code>16044</odt-klant-regel-code><odt-artikel-code>00011</odt-artikel-code><odt-stuks-besteld>1</odt-stuks-besteld></detail></details></order></orders></bericht>',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/xml'
                      ),
                    ));
                    
                    $response = curl_exec($curl);
                    
                    curl_close($curl);




                    // if($response->failed()) {
                    //     $err = $response->status();
                    //     if($response->serverError()) $err .= ' serverError';
                    //     if($response->clientError()) $err .= ' clientError';
                    //     $sendOrder->errors = $err;
                    // }

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

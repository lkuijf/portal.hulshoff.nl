<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Productbrand;
use App\Models\Productgroup;
use App\Models\Producttype;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class xmlController extends Controller
{
    public function savePostedXml(Request $request, $xmltype) {
        if(!isset($xmltype) || !in_array($xmltype, ['klant','artikel','order','vrdstand'])) return abort(404);

        if(request()->isXml()) {
            $body =  $request->getContent();
            Storage::disk('local_xml_' . $xmltype)->put($xmltype . '-' . date("Ymd-His") . '-' . str_pad(rand(0,9999),4,0,STR_PAD_LEFT) . '.xml', $body); // filename like: klant-20221024-141151-4898.xml
        } else {
            return abort(404);
        }
    }

    public function getObjectFromXml($file) {
        $data = new \stdClass();
        try {
            $xmlFile = file_get_contents(public_path($file));
            $xmlObject = simplexml_load_string($xmlFile);
            $jsonFormattedData = json_encode($xmlObject);
            $data->xmldata = json_decode($jsonFormattedData);
        } catch (\Exception $e) {
            $data->error = $e->getMessage();
        }
        return $data;
    }
    public function importXml($type) {
        // if($type == 'klanten') $xmlFile = file_get_contents(public_path('xml/klanten.xml'));

        if($type == 'wmsorders') {

        }

        if($type == 'producten') {
            $xmlLocation = 'xml/artikelen.xml';
            $data = $this->getObjectFromXml($xmlLocation);
            if(isset($data->xmldata)) {
                if(isset($data->xmldata->artikelen->artikel) && count($data->xmldata->artikelen->artikel)) {
                    $result = $this->upsertProducts($data->xmldata->artikelen->artikel);
                    $data = [
                        'result' => 'result message: ' . $result->msg,
                    ];
                    return view('templates.parseXml_index')->with('data', $data);
                } else {
                    // write to db
                    // display message geen nodes gevonden.
                }
            }
            if(isset($data->error)) {
                // write to db
                // display message
            }
        }
        
        if($type == 'klanten') {
            $xmlLocation = 'xml/klanten.xml';
            $data = $this->getObjectFromXml($xmlLocation);
            if(isset($data->xmldata)) {
                if(isset($data->xmldata->klanten->klant) && count($data->xmldata->klanten->klant)) {
                    $result = $this->upsertCustomers($data->xmldata->klanten->klant);
                    $data = [
                        'result' => 'result message: ' . $result->msg,
                    ];
                    return view('templates.parseXml_index')->with('data', $data);
                } else {
                    // write to db
                    // display message geen nodes gevonden.
                }
            }
            if(isset($data->error)) {
                // write to db
                // display message
            }
        }

        if($type == 'voorraden') {
            $xmlLocation = 'xml/voorraden.xml';
            $data = $this->getObjectFromXml($xmlLocation);
            if(isset($data->xmldata)) {
                if(isset($data->xmldata->voorraden->voorraad) && count($data->xmldata->voorraden->voorraad)) {
                    $result = $this->updateVoorraden($data->xmldata->voorraden->voorraad);
                    $data = [
                        'result' => 'result message: ' . $result->msg,
                    ];
                    return view('templates.parseXml_index')->with('data', $data);
                } else {
                    // write to db
                    // display message geen nodes gevonden.
                }
            }
            if(isset($data->error)) {
                // write to db
                // display message
            }
        }
        
    }

    public function updateVoorraden($stocks) {
        $res = new \stdClass();
        foreach($stocks as $stock) {
            $totalAffected = Product::where([
                'klantCode' => $stock->{'vrr-klant-code'},
                'artikelCode' => $stock->{'vrr-artikel-code'}
                ])->update(['minimaleVoorraad' => $stock->{'vrr-aantal-stuks'}]);
        }
        $res->msg = 'success';
        return $res;
    }

    public function upsertCustomers($customers) {
        $res = new \stdClass();
        foreach($customers as $cust) {
            Customer::updateOrCreate(
                ['klantCode' => $cust->{'kla-klant-code'}],
                [
                    'naam' => $cust->{'kla-naam'},
                    'straat' => $cust->{'kla-straat'},
                    'huisnummer' => $cust->{'kla-huisnummer'},
                    'postcode' => $cust->{'kla-postcode'},
                    'landCode' => $cust->{'kla-land-code'},
                    'contactpersoon' => $cust->{'kla-contactpersoon'},
                    'telefoon' => $cust->{'kla-telefoon'},
                    'eMailadres' => $cust->{'kla-e-mailadres'},
                    'website' => $cust->{'kla-website'},
                ]
            );
        }
        $res->msg = 'success';
        return $res;
    }

    public function upsertProducts($products) {
        $res = new \stdClass();
        foreach($products as $prod) {
            $productgroup = Productgroup::firstOrCreate([
                'code' => $prod->{'art-artikelgroep-code'}
            ]);
            $productbrand = Productbrand::firstOrCreate([
                'brand' => $prod->{'art-merk'}
            ]);
            $producttype = Producttype::firstOrCreate([
                'type' => $prod->{'art-type'}
            ]);
            $customer = Customer::firstOrCreate([
                'klantCode' => $prod->{'art-klant-code'}
            ]);
            $product = Product::updateOrCreate(
                ['klantCode' => $prod->{'art-klant-code'}, 'artikelCode' => $prod->{'art-artikel-code'}],
                [
                    'omschrijving' => $prod->{'art-omschrijving'},
                    'stuksPerBundel' => $prod->{'art-stuks-per-bundel'},
                    'prijs' => $prod->{'art-prijs'},
                    'minimaleVoorraad' => $prod->{'art-minimale-voorraad'},
                    'bijzonderheden' => $prod->{'art-bijzonderheden'},
                    'kleur' => $prod->{'art-kleur'},
                    'lengte' => $prod->{'art-lengte'},
                    'breedte' => $prod->{'art-breedte'},
                    'hoogte' => $prod->{'art-hoogte'},
                    'productgroup_id' => $productgroup->id,
                    'productbrand_id' => $productbrand->id,
                    'producttype_id' => $producttype->id
                ]
            );
        }
        $res->msg = 'success';
        return $res;
    }
}

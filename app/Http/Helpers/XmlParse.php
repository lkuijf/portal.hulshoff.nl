<?php
namespace App\Http\Helpers;
use Illuminate\Support\Facades\Storage;
use App\Models\LogXmlParse;
use App\Models\Product;
use App\Models\Productbrand;
use App\Models\Productgroup;
use App\Models\Producttype;
use App\Models\Customer;
use App\Models\WmsOrder;
use App\Models\WmsOrderArticle;

class XmlParse {

    public function __construct() {
    }

    public static function parseIt($type) {
echo "\n";
echo 'parseIt: ' . $type . "\n";

        
        $aFiles = [];
        foreach(Storage::disk('local_xml_' . $type)->files() as $file) {
// echo $file . "\n";
            $foundRec = LogXmlParse::where('file', $type . '/' . $file)->firstOr(function () use ($type, $file) { // check if not already parsed
                $logParse = new LogXmlParse;
                $errors = [];
                $fileLocation = Storage::disk('local_xml_' . $type)->path($file);
// echo $fileLocation . "\n";
                $data = self::getObjectFromXml($fileLocation);
                if(isset($data->xmldata)) {
                    if($type == 'vrdstand' && isset($data->xmldata->voorraden->voorraad) && count($data->xmldata->voorraden->voorraad)) {
                        $result = self::updateVoorraden($data->xmldata->voorraden->voorraad);
                    } elseif($type == 'artikel' && isset($data->xmldata->artikelen->artikel) && count($data->xmldata->artikelen->artikel)) {
                        $result = self::upsertProducts($data->xmldata->artikelen->artikel);
                    } elseif($type == 'klant' && isset($data->xmldata->klanten->klant) && count($data->xmldata->klanten->klant)) {
                        $result = self::upsertCustomers($data->xmldata->klanten->klant);
                    } elseif($type == 'order' && isset($data->xmldata->orders->order)) {
                        if(!is_array($data->xmldata->orders->order)) $data->xmldata->orders->order = array($data->xmldata->orders->order);
                        if(count($data->xmldata->orders->order)) {
                            $result = self::insertWmsOrders($data->xmldata->orders->order);
                        }
                    } else {
                        $errors[] = 'No nodes found'; // write to db. no nodes found.
                    }
                }
                if(isset($data->error)) {
                    $errors[] = $data->error; // write to db. Exception.
                }
                if(count($errors)) $logParse->errors = substr(implode(' & ', $errors), 0, 200);
                $logParse->file = substr($type . '/' . $file, 0, 200);
                $logParse->save();
            });
            if($foundRec) {
                // some notice?
echo 'ALREADY PARSED: ' . $type . '/' . $file . "\n";
            }

        }

        
    }

    public static function getObjectFromXml($file) {
        $data = new \stdClass();
        try {
            $xmlFile = file_get_contents($file);
            $xmlObject = simplexml_load_string($xmlFile);
            $jsonFormattedData = json_encode($xmlObject);
            $data->xmldata = json_decode($jsonFormattedData);
        } catch (\Exception $e) {
            $data->error = $e->getMessage();
        }
        return $data;
    }

    public static function updateVoorraden($stocks) {
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

    public static function upsertCustomers($customers) {
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

    public static function upsertProducts($products) {
        $res = new \stdClass();
        foreach($products as $prod) {
            $productgroup = Productgroup::firstOrCreate([
                'group' => $prod->{'art-artikelgroep-code'}
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

    public static function insertWmsOrders($orders) {
        $res = new \stdClass();
        foreach($orders as $ord) {
            $customer = Customer::firstOrCreate([
                'klantCode' => $ord->{'ord-klant-code'}
            ]);
            $wmsOrder = WmsOrder::firstOrCreate(
                ['orderCodeKlant' => $ord->{'ord-order-code-klant'}, 'orderCodeAflever' => $ord->{'ord-order-code-aflever'}],
                [
                    'klantCode' => $ord->{'ord-klant-code'},
                    'orderNr' => $ord->{'ord-order-nr'},
                    'ataAleverenDatum' => $ord->{'ord-ata-afleveren-datum'},
                    'ataAleverenTijd' => $ord->{'ord-ata-afleveren-tijd'}
                ]
            );
            if(isset($ord->details->detail)) {
                if(!is_array($ord->details->detail)) $ord->details->detail = array($ord->details->detail);
                if(count($ord->details->detail)) {
                    foreach($ord->details->detail as $det) {
                        $wmsOrderArticle = WmsOrderArticle::firstOrCreate(
                            ['wms_order_id' => $wmsOrder->id, 'artikelCode' => $det->{'odt-artikel-code'},],
                            [
                                'stuksUitgeleverd' => $det->{'odt-stuks-uitgeleverd'},
                            ]
                        );
                    }
                }
            }
        }
        $res->msg = 'success';
        return $res;
    }

}
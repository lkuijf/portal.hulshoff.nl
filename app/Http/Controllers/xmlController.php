<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Productbrand;
use App\Models\Productgroup;
use App\Models\Producttype;
use App\Models\Customer;
use App\Models\WmsOrder;
use App\Models\WmsOrderArticle;
use App\Models\LogXmlPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\XmlParse;

class xmlController extends Controller
{
    public function savePostedXml(Request $request, $xmltype) {
        if(!isset($xmltype) || !in_array($xmltype, ['klant','artikel','order','vrdstand'])) return abort(404);

        $log = new LogXmlPost;
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $log->requestedUrl = $_SERVER['REQUEST_URI'];
        $log->save();

        if(request()->isXml()) {
            $body = $request->getContent();
            // $filename = $xmltype . '-' . date("Ymd-His") . '-' . str_pad(rand(0,9999),4,0,STR_PAD_LEFT) . '.xml';
            $t = 1;
            $filenamePart = $xmltype . '-' . date("Ymd-His") . '-';
            while(Storage::disk('local_xml_' . $xmltype)->exists($filenamePart . $t . '.xml')) $t++;
            Storage::disk('local_xml_' . $xmltype)->put($filenamePart . $t . '.xml', $body); // filename like: klant-20221024-141151-4898.xml
        } else {
            return abort(404);
        }
    }

    public function importXml($type) {
        // if($type == 'klanten') $xmlFile = file_get_contents(public_path('xml/klanten.xml'));

        if($type == 'wmsorders') {
            
            $xmlLocation = public_path('xml/orders.xml');
            $data = XmlParse::getObjectFromXml($xmlLocation);

            if(isset($data->xmldata)) {

                if(isset($data->xmldata->orders->order)) {
                    if(!is_array($data->xmldata->orders->order)) $data->xmldata->orders->order = array($data->xmldata->orders->order);
                
                    if(count($data->xmldata->orders->order)) {
                        $result = XmlParse::insertWmsOrders($data->xmldata->orders->order);
                        $data = [
                            'include_view' => 'development.xml',
                            'result' => 'result message: ' . $result->msg,
                        ];
                        return view('templates.development')->with('data', $data);
                    } else {
                        // write to db
                        // display message geen nodes gevonden.
                    }
                }

            }
            if(isset($data->error)) {
                echo $data->error;
                // write to db
                // display message
            }
        }

        if($type == 'producten') {
            $xmlLocation = public_path('xml/artikelen.xml');
            $data = XmlParse::getObjectFromXml($xmlLocation);
            if(isset($data->xmldata)) {
                if(isset($data->xmldata->artikelen->artikel) && count($data->xmldata->artikelen->artikel)) {
                    $result = XmlParse::upsertProducts($data->xmldata->artikelen->artikel);
                    $data = [
                        'include_view' => 'development.xml',
                        'result' => 'result message: ' . $result->msg,
                    ];
                    return view('templates.development')->with('data', $data);
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
            $xmlLocation = public_path('xml/klanten.xml');
            $data = XmlParse::getObjectFromXml($xmlLocation);
            if(isset($data->xmldata)) {
                if(isset($data->xmldata->klanten->klant) && count($data->xmldata->klanten->klant)) {
                    $result = XmlParse::upsertCustomers($data->xmldata->klanten->klant);
                    $data = [
                        'include_view' => 'development.xml',
                        'result' => 'result message: ' . $result->msg,
                    ];
                    return view('templates.development')->with('data', $data);
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
            $xmlLocation = public_path('xml/voorraden.xml');
            $data = XmlParse::getObjectFromXml($xmlLocation);
            if(isset($data->xmldata)) {
                if(isset($data->xmldata->voorraden->voorraad) && count($data->xmldata->voorraden->voorraad)) {
                    // $result = $this->updateVoorraden($data->xmldata->voorraden->voorraad);
                    $result = XmlParse::updateVoorraden($data->xmldata->voorraden->voorraad);
                    $data = [
                        'include_view' => 'development.xml',
                        'result' => 'result message: ' . $result->msg,
                    ];
                    return view('templates.development')->with('data', $data);
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
}

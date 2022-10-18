<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Productbrand;
use App\Models\Productgroup;
use App\Models\Producttype;
use Illuminate\Http\Request;

class xmlController extends Controller
{
    public function parseXml() {
        $xmlFile = file_get_contents(public_path('xml/artikel.xml'));

        $xmlObject = simplexml_load_string($xmlFile);

        $jsonFormattedData = json_encode($xmlObject);
        // $result = json_decode($jsonFormattedData, true); 
        $result = json_decode($jsonFormattedData); 

// dd($result);

        if(isset($result->artikelen->artikel) && count($result->artikelen->artikel)) {
            // $this->saveProducts($result->artikelen->artikel);
            $this->upsertProducts($result->artikelen->artikel);
        }
    }

    public function upsertProducts($products) {
        foreach($products as $prod) {
// dd($prod);

            $productgroup = Productgroup::firstOrCreate([
                'code' => $prod->{'art-artikelgroep-code'}
            ]);
            $productbrand = Productbrand::firstOrCreate([
                'brand' => $prod->{'art-merk'}
            ]);
            $producttype = Producttype::firstOrCreate([
                'type' => $prod->{'art-type'}
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
// var_dump($product);
       }
       echo '-end of upserting-';
    }

    public function saveProducts($products) {
        // dd($products);
        foreach($products as $prod) {
            $product = new Product;
            $productbrand = new Productbrand;
            $productgroup = new Productgroup;
            $producttype = new Producttype;

            $pgId = $productgroup->insertGetId(['code' => $prod->{'art-artikelgroep-code'}]);
            $pbId = $productbrand->insertGetId(['brand' => $prod->{'art-merk'}]);
            $ptId = $producttype->insertGetId(['type' => $prod->{'art-type'}]);

            $product->klantCode = $prod->{'art-klant-code'};
            $product->artikelCode = $prod->{'art-artikel-code'};
            $product->omschrijving = $prod->{'art-omschrijving'};
            $product->stuksPerBundel = $prod->{'art-stuks-per-bundel'};
            $product->prijs = $prod->{'art-prijs'};
            $product->minimaleVoorraad = $prod->{'art-minimale-voorraad'};
            $product->bijzonderheden = $prod->{'art-bijzonderheden'};
            $product->kleur = $prod->{'art-kleur'};
            $product->lengte = $prod->{'art-lengte'};
            $product->breedte = $prod->{'art-breedte'};
            $product->hoogte = $prod->{'art-hoogte'};

            $product->productgroup_id = $pgId;
            $product->productbrand_id = $pbId;
            $product->producttype_id = $ptId;

            $product->save();
        }
        echo '-end of saving-';
    }
}

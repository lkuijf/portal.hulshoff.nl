<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Customer;
// use App\Models\Productbrand;
// use App\Models\Productgroup;
// use App\Models\Producttype;
// use App\Models\Productcolor;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function showProducts() {
        if(!auth()->user()->canDisplay()) return view('no-data');

// dd(Storage::disk('network_images')->get('50246/00044.jpg'));
// dd(Storage::disk('network_images')->files('50246'));
// dd(Storage::disk('network_images')->allDirectories('50246'));

        // $bShowTiles = false;
        $filterToShow = 'side';
        $privileges = json_decode(auth()->user()->privileges);
        if($privileges) {
            if(in_array('filter_on_top', $privileges)) $filterToShow = 'top';
            if(in_array('filter_at_side', $privileges)) $filterToShow = 'side';
            // if(in_array('show_tiles', $privileges)) $bShowTiles = true;
        }

        $selectedKlantCode = false;
        if(session()->has('selectedClient')) $selectedKlantCode = session('selectedClient');
        $customerBrands = $this->getSpecs('brand', $selectedKlantCode);
        $customerGroups = $this->getSpecs('group', $selectedKlantCode);
        $customerTypes = $this->getSpecs('type', $selectedKlantCode);
        $customerColors = $this->getSpecs('color', $selectedKlantCode);
        
        $tiles = DB::table('tiles')->get();      

        $aBrands = [];
        $aGroups = [];
        $aTypes = [];
        $aColors = [];
        $aTiles = [];
        foreach($customerBrands as $brand) $aBrands[$brand->id] = $brand->brand;
        foreach($customerGroups as $group) $aGroups[$group->id] = $group->group;
        foreach($customerTypes as $type) $aTypes[$type->id] = $type->type;
        foreach($customerColors as $color) $aColors[$color->id] = $color->color;
        foreach($tiles as $tile) $aTiles[$tile->group] = $tile->file;
        
        $data = [
            // 'tilesDisplay' => $bShowTiles,
            'filterDisplay' => $filterToShow,
            'tiles' => $aTiles,
            'filters' => [
                'group' => ['name' => __('Group'), 'items' => $aGroups],
                'type' => ['name' => 'Type', 'items' => $aTypes],
                'brand' => ['name' => __('Brand'), 'items' => $aBrands],
                'color' => ['name' => __('Color'), 'items' => $aColors],
            ],
        ];
        return view('productOverview')->with('data', $data);
    }

    public function getProducts(Request $filters) {
        $resQry = Product::select();
        // if(auth()->user()->klantCode) $resQry->where('klantCode', auth()->user()->klantCode);
        if(isset($filters->c_code) && $filters->c_code['value']) {
            $resQry->where('klantCode', $filters->c_code['value']);
        }
        if(isset($filters->brand) && $filters->brand['value']) {
            $resQry->where('productbrand_id', $filters->brand['value']);
        }
        if(isset($filters->group) && $filters->group['value']) {
            $resQry->where('productgroup_id', $filters->group['value']);
        }
        if(isset($filters->type) && $filters->type['value']) {
            $resQry->where('producttype_id', $filters->type['value']);
        }
        if(isset($filters->color) && $filters->color['value']) {
            $resQry->where('productcolor_id', $filters->color['value']);
        }
        if(isset($filters->search) && $filters->search['value']) {
            $resQry->where(function($resQry) use ($filters) {
                $resQry
                ->where('omschrijving', 'like', '%' . $filters->search['value'] . '%')
                ->orWhere('bijzonderheden', 'like', '%' . $filters->search['value'] . '%')
                ->orWhere('artikelCode', 'like', '%' . $filters->search['value'] . '%')
                ->orWhere('artikelCodeKlant', 'like', '%' . $filters->search['value'] . '%')
                ->orWhere('verpakkingBundel', 'like', '%' . $filters->search['value'] . '%')
                ;
            });
        }
        if(isset($filters->onlyinstock) && $filters->onlyinstock == true) {
            $resQry->where('voorraad', '>', 0);
        }
        $resQry->orderBy('omschrijving', 'asc');
        $res = $resQry->paginate(10);

// dd(Storage::disk('product_images')->url('/50246/00044.jpg'));
        foreach($res as &$product) {
            if(env('APP_ENV') == 'production') {
                $productImageUrl = 'https://via.placeholder.com/800x600?text=Geen+afbeelding+gevonden';
                $imgUrlPart = $product['klantCode'] . '/' . $product['artikelCode'];
                if(Storage::disk('product_images')->exists($imgUrlPart . '.jpg')) $productImageUrl = Storage::disk('product_images')->url($imgUrlPart . '.jpg');
                if(Storage::disk('product_images')->exists($imgUrlPart . '.JPG')) $productImageUrl = Storage::disk('product_images')->url($imgUrlPart . '.JPG');
            } else {
                $productImageUrl = 'https://via.placeholder.com/800x600?text=No+production+environment';
            }
            $product['imageUrl'] = $productImageUrl;
        }
        

        $data = [
            'products' => $res,
            'totalPages' => $res->lastPage(),
            'currentPage' => $res->currentPage(),
        ];

        return view('snippets.productList')->with('data', $data);
    }

    public function getSpecs($spec, $klantCode = false) {
        $resQry = DB::table('product' . $spec . 's')
            // ->join('products', 'products.product' . $spec . '_id', '=', 'product' . $spec . 's.id')
            ->select('product' . $spec . 's.*')
            ->distinct();
        if($klantCode) $resQry->join('products', 'products.product' . $spec . '_id', '=', 'product' . $spec . 's.id');
        if($klantCode) $resQry->where('products.klantCode', '=', $klantCode);
        if($spec == 'brand') $resQry->orderBy('brand', 'asc');
        if($spec == 'group') $resQry->orderBy('group', 'asc');
        if($spec == 'type') $resQry->orderBy('type', 'asc');
        if($spec == 'color') $resQry->orderBy('color', 'asc');
        $results = $resQry->get();
        return $results;
    }

    public function showProductDetails($id) {
        if(!auth()->user()->canDisplay()) return view('no-data');

        $product = Product::findOr($id, function () {
            return abort(404);
        });
        if(env('APP_ENV') == 'production') {
            $productImageUrl = 'https://via.placeholder.com/800x600?text=Geen+afbeelding+gevonden';
            $imgUrlPart = $product->klantCode . '/' . $product->artikelCode;
            if(Storage::disk('product_images')->exists($imgUrlPart . '.jpg')) $productImageUrl = Storage::disk('product_images')->url($imgUrlPart . '.jpg');
            if(Storage::disk('product_images')->exists($imgUrlPart . '.JPG')) $productImageUrl = Storage::disk('product_images')->url($imgUrlPart . '.JPG');
        } else {
            $productImageUrl = 'https://via.placeholder.com/800x600?text=No+production+environment';
        }
        $product->imageUrl = $productImageUrl;


        if(session()->has('selectedClient' ) && (session('selectedClient') != $product->klantCode)) return view('no-data');

        return view('productDetail')->with('product', $product);
    }

    public function getTypes(Request $req) {
        $resQry = DB::table('producttypes')
            ->join('products', 'products.producttype_id', '=', 'producttypes.id')
            ->where('products.productgroup_id', $req->groupId)
            ->select('producttypes.id', 'producttypes.type')
            ->distinct();
        $results = $resQry->get();
        echo json_encode($results);
    }

    public function getBrands(Request $req) {
        $resQry = DB::table('productbrands')
            ->join('products', 'products.productbrand_id', '=', 'productbrands.id')
            ->where('products.productgroup_id', $req->groupId)
            ->where('products.producttype_id', $req->typeId)
            ->select('productbrands.id', 'productbrands.brand')
            ->distinct();
        if($req->colorId) $resQry->where('products.productcolor_id', $req->colorId);
        $results = $resQry->get();
        echo json_encode($results);
    }

    public function getColors(Request $req) {
        $resQry = DB::table('productcolors')
            ->join('products', 'products.productcolor_id', '=', 'productcolors.id')
            ->where('products.productgroup_id', $req->groupId)
            ->where('products.producttype_id', $req->typeId)
            ->select('productcolors.id', 'productcolors.color')
            ->distinct();
        if($req->brandId) $resQry->where('products.productbrand_id', $req->brandId);
        $results = $resQry->get();
        echo json_encode($results);
    }

}

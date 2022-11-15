<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Productbrand;

class ProductController extends Controller
{
    public function showProducts() {
        if(!auth()->user()->canDisplay()) return view('no-data');
        
        $customerBrands = $this->getSpecs('brand', auth()->user()->klantCode);
        $customerGroups = $this->getSpecs('group', auth()->user()->klantCode);
        $customerTypes = $this->getSpecs('type', auth()->user()->klantCode);

        $aBrands = [];
        $aGroups = [];
        $aTypes = [];
        foreach($customerBrands as $brand) $aBrands[] = $brand->brand;
        foreach($customerGroups as $group) $aGroups[] = $group->group;
        foreach($customerTypes as $type) $aTypes[] = $type->type;
        
        $data = [
            'filters' => [
                'brand' => ['name' => 'Merk', 'items' => $aBrands],
                'group' => ['name' => 'Groep', 'items' => $aGroups],
                'type' => ['name' => 'Type', 'items' => $aTypes],
            ],
        ];
        return view('productOverview')->with('data', $data);
    }

    public function getProducts(Request $filters) {
        $resQry = Product::select();
        if(auth()->user()->klantCode) $resQry->where('klantCode', auth()->user()->klantCode);

        if($filters->brand) {
            $brand = Productbrand::where('brand', $filters->brand)->first();
            $resQry->where('productbrand_id', $brand->id);
        }

        $res = $resQry->paginate(3);

        $data = [
            'products' => $res,
            'totalPages' => $res->lastPage(),
            'currentPage' => $res->currentPage(),
        ];
        return view('snippets.productList')->with('data', $data);
    }

    public function getSpecs($spec, $klantCode = false) {
        $resQry = DB::table('product' . $spec . 's')
            ->join('products', 'products.product' . $spec . '_id', '=', 'product' . $spec . 's.id')
            ->select('product' . $spec . 's.*')
            ->distinct();
        if($klantCode) $resQry->where('products.klantCode', '=', $klantCode);
        $results = $resQry->get();
        return $results;
    }
   
}

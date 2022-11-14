<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function showProducts() {
        // 
        return view('productOverview');
    }
    public function getProducts() {
        $res = Product::paginate(3);
        // return $products;
// dd($res);
        $data = [
            'products' => $res,
            'totalPages' => $res->lastPage(),
            'currentPage' => $res->currentPage(),
        ];
        return view('snippets.productList')->with('data', $data);
    }
}

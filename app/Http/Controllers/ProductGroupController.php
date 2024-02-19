<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Productgroup;
use App\Models\Tile;

class ProductGroupController extends Controller
{
    public function delete(Request $request) {
        $productgroup = Productgroup::find($request->id);
        // Productgroup::destroy($request->id);

        $tile = Tile::where('group', $productgroup->group)->first();
        if($tile) {
            Storage::disk('tiles')->delete($tile->file);
            $tile->delete();
        }

        $productgroup->delete();

        $request->session()->flash('message', '<p>Productgroup removed</p>');
        return redirect()->back();
    }
}

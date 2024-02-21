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

        $request->session()->flash('message', '<p>' . __('Productgroup') . ' ' . __('removed') . '</p>');
        return redirect()->back();
    }

    public function store(Request $request) {

        if(Productgroup::where('group', $request->group_name)->first()) {
            // $request->session()->flash('error', '<p>' . __('Productgroup') . ' "' . $request->group_name . '" ' . __('already exists') . '</p>');
            // return redirect()->back();
            return redirect()->back()->withErrors([__('Productgroup') . ' `' . $request->group_name . '` ' . __('already exists')]);
        }

        $productgroup = Productgroup::find($request->id);
        $productgroup->group = $request->group_name;
        $productgroup->save();
        $request->session()->flash('message', '<p>' . __('Productgroup') . ' ' . __('changed') . '</p>');
        return redirect()->back();
    }
}

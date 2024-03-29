<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Tile;

class TilesController extends Controller
{
    public function showTiles() {
        if(!auth()->user()->is_admin || !auth()->user()->email_verified_at) return view('no-access');

        $allGroups = DB::table('productgroups')->orderBy('group', 'asc')->get();

        foreach($allGroups as &$group) {
            $allGroupProducts = DB::table('products')->where('productgroup_id', $group->id)->get();
            $group->productCount = $allGroupProducts->count();
        }
// dd($allGroups);

        $allTiles = DB::table('tiles')->get();
        $aTilesByGroup = [];
        foreach($allTiles as $tile) {
            $aTilesByGroup[$tile->group] = $tile->file;
        }
        $data = [
            'all_groups' => $allGroups,
            'all_tiles_by_group' => $aTilesByGroup,
        ];
        return view('tilesList')->with('data', $data);
    }

    public function uploadTile(Request $request) {
        $toValidate = array(
            'tileFile' => 'required',
        );
        $validationMessages = array(
            'tileFile.required'=> 'Selecteer a.u.b. een bestand.',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $path = $request->file('tileFile')->store('tiles');
        $uploadedFile = [];
        $uploadedFile['group'] = $request->group_name;
        $uploadedFile['file'] = basename($path);

        $tile = new Tile;
        $tile->group = $uploadedFile['group'];
        $tile->file = $uploadedFile['file'];
        $tile->save();

        $request->session()->flash('message', '<p>' . __('Tile') . ' ' . __('added') . '</p>');
        return redirect()->back();
    }

    public function deleteTile(Request $request) {
        $tile = Tile::where('group', $request->group_name)->first();
        Storage::disk('tiles')->delete($tile->file);
        $tile->delete();

        $request->session()->flash('message', '<p>' . __('Tile') . ' ' . __('removed') . '</p>');
        return redirect()->back();
    }

}

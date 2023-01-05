<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public function updateEntry($name, $start, $end, $info) {
        $aName = explode('\\', $name); // $name is classname with full namespace
        $this->name = $aName[sizeof($aName)-1];
        $this->start_time = $start;
        $this->end_time = $end;
        $this->total_files = $info['total'];
        $this->processed = $info['processed'];
        $this->skipped = $info['skipped'];
        $this->save();
    }
}

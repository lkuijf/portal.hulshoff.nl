<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $primaryKey = 'klantCode';

    public function addresses() {
        return $this->hasMany(Address::class, 'klantCode', 'klantCode');
    }

}

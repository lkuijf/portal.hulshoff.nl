<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'klantCode',
    //     'artikelCode',
    //     'omschrijving',
    //     'stuksPerBundel',
    //     'prijs',
    //     'minimaleVoorraad',
    //     'bijzonderheden',
    //     'kleur',
    //     'lengte',
    //     'breedte',
    //     'hoogte',
    //     'productgroup_id',
    //     'productbrand_id',
    //     'producttype_id',
    // ];
    protected $guarded = [];

    public function brand() {
        return $this->hasOne(Productbrand::class, 'id', 'productbrand_id');
    }
    public function group() {
        return $this->hasOne(Productgroup::class, 'id', 'productgroup_id');
    }
    public function type() {
        return $this->hasOne(Producttype::class, 'id', 'producttype_id');
    }
}

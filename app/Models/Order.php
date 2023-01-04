<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderArticles() {
        return $this->hasMany(OrderArticle::class);
    }
    public function hulshoffUser() {
        return $this->hasOne(HulshoffUser::class, 'id', 'hulshoff_user_id');
    }
}
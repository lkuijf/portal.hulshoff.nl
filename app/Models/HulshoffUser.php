<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class HulshoffUser extends Authenticatable
{
    use HasFactory, TwoFactorAuthenticatable;

    public function canDisplay() {
        if(!$this->is_admin && !$this->klantCode) {
            return false;
        }
        return true;
    }
}

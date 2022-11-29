<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class HulshoffUser extends Authenticatable
{
    use HasFactory, TwoFactorAuthenticatable, Notifiable, MustVerifyEmail;

    public function canDisplay() {
        if(!$this->is_admin && !$this->klantCode) {
            return false;
        }
        return true;
    }
}

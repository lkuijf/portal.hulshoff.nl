<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class HulshoffUser extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, TwoFactorAuthenticatable, Notifiable;

    // public function customer() {
    //     return $this->hasOne(Customer::class, 'klantCode', 'klantCode');
    // }

    public function clientCodes() {
        return $this->hasMany(HulshoffUserKlantcode::class);

        // $customers = [];
        // foreach($userClients as $uc) {
        //     $customers[] = new Customer($uc->klantCode);
        // }
        // return $customers;
    }

    public function canDisplay() {
        // if((!$this->is_admin && !$this->klantCode) || !$this->email_verified_at) {

        if(!$this->email_verified_at || !$this->two_factor_confirmed_at || (!$this->is_admin && !session()->has('selectedClient' ))) {
                return false;
        }
        
        return true;
    }
}

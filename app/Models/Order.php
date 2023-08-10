<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    public function orderArticles() {
        return $this->hasMany(OrderArticle::class);
    }

    public function hulshoffUser() {
        return $this->hasOne(HulshoffUser::class, 'id', 'hulshoff_user_id');
    }

    public function customer() {
        return $this->hasOne(Customer::class, 'klantCode', 'klantCode');
    }

    public function address() {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function custom_address() {
        return $this->hasOne(CustomAddress::class, 'id', 'custom_address_id');
    }

    public function generateXml() {
        $aAdressen = [];
        $aDetails = [];
        $orderXml = new \SimpleXMLElement('<bericht></bericht>');
        $orderXml->addChild('bericht-type', 'IFC-ORDER-IN');
        $orderXml->addChild('bericht-id', $this->id);
        $orders = $orderXml->addChild('orders');
        $order = $orders->addChild('order');
        // $order->addChild('ord-klant-code', ($this->hulshoffUser?$this->hulshoffUser->klantCode:'-deleted user-'));
        $order->addChild('ord-klant-code', $this->klantCode);
        $order->addChild('ord-order-code-klant', $this->orderCodeKlant);
        // $order->addChild('ord-order-code-aflever', $this->orderCodeAflever);
        $order->addChild('ord-order-code-aflever', '2507501');
        $order->addChild('ord-eta-afleveren-datum', $this->afleverDatum);
        $order->addChild('ord-eta-afleveren-tijd', $this->afleverTijd);
        $adressen = $order->addChild('adressen');

        $aAdressen[0] = $adressen->addChild('adres');

        if($this->address_id) { // when the order is connected to an address
            $aAdressen[0]->addChild('afa-afleveradres-code', 'ALGEMEEN'); // ??
            $aAdressen[0]->addChild('afa-naam', $this->address->naam);
            $aAdressen[0]->addChild('afa-straat', $this->address->straat);
            $aAdressen[0]->addChild('afa-huisnummer', $this->address->huisnummer);
            $aAdressen[0]->addChild('afa-postcode', $this->address->postcode);
            $aAdressen[0]->addChild('afa-plaats', $this->address->plaats);
            $aAdressen[0]->addChild('afa-land-code', $this->address->landCode);
            $aAdressen[0]->addChild('afa-contactpersoon', $this->address->contactpersoon);
            $aAdressen[0]->addChild('afa-telefoon', $this->address->telefoon);
            $aAdressen[0]->addChild('afa-e-mailadres', $this->address->eMailadres);
        } elseif($this->custom_address_id) {
            $aAdressen[0]->addChild('afa-afleveradres-code', 'ALGEMEEN'); // ??
            $aAdressen[0]->addChild('afa-naam', 'HANDMATIG');
            $aAdressen[0]->addChild('afa-straat', $this->custom_address->straat);
            $aAdressen[0]->addChild('afa-huisnummer', $this->custom_address->huisnummer);
            $aAdressen[0]->addChild('afa-postcode', $this->custom_address->postcode);
            $aAdressen[0]->addChild('afa-plaats', $this->custom_address->plaats);
            $aAdressen[0]->addChild('afa-land-code', 'NL');
            $aAdressen[0]->addChild('afa-contactpersoon', $this->custom_address->contactpersoon);
            $aAdressen[0]->addChild('afa-telefoon', $this->custom_address->telefoon);
            $aAdressen[0]->addChild('afa-e-mailadres', 'planning@hulshoff.nl');
        } else {
            $aAdressen[0]->addChild('afa-afleveradres-code', 'ALGEMEEN'); // ??
            $aAdressen[0]->addChild('afa-naam', '-niet bekend-');
            $aAdressen[0]->addChild('afa-straat', '-niet bekend-');
            $aAdressen[0]->addChild('afa-huisnummer', '00');
            $aAdressen[0]->addChild('afa-postcode', '9999XX');
            $aAdressen[0]->addChild('afa-plaats', '-niet bekend-');
            $aAdressen[0]->addChild('afa-land-code', 'NL');
            $aAdressen[0]->addChild('afa-contactpersoon', '-niet bekend-');
            $aAdressen[0]->addChild('afa-telefoon', '0600112233');
            $aAdressen[0]->addChild('afa-e-mailadres', '-niet bekend-');

        }

        $details = $order->addChild('details');
        if(count($this->orderArticles)) {
            foreach($this->orderArticles as $i => $ordArt) {
                $aDetails[$i] = $details->addChild('detail');
                $aDetails[$i]->addChild('odt-klant-regel-code', '16044'); // ??
                $aDetails[$i]->addChild('odt-artikel-code', $ordArt->product->artikelCode);
                $aDetails[$i]->addChild('odt-stuks-besteld', $ordArt->amount);
            }
        }

        $filename = 'order-' . $this->id . '-' . date("Ymd-His") . '.xml';
        $orderXml->asXML(Storage::disk('local_xml_order_out')->path($filename));

        return true;
    }
}

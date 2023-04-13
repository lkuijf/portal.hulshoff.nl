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

        if($this->customer) { // when the order is connected to a customer
            $aAdressen[0] = $adressen->addChild('adres');
            if($this->customer->naam) {
                $aAdressen[0]->addChild('afa-afleveradres-code', 'ALGEMEEN'); // ??
                $aAdressen[0]->addChild('afa-naam', $this->customer->naam);
                $aAdressen[0]->addChild('afa-straat', $this->customer->straat);
                $aAdressen[0]->addChild('afa-huisnummer', $this->customer->huisnummer);
                $aAdressen[0]->addChild('afa-postcode', $this->customer->postcode);
                $aAdressen[0]->addChild('afa-plaats', $this->customer->plaats);
                $aAdressen[0]->addChild('afa-land-code', $this->customer->landCode);
                $aAdressen[0]->addChild('afa-contactpersoon', $this->customer->contactpersoon);
                $aAdressen[0]->addChild('afa-telefoon', $this->customer->telefoon);
                $aAdressen[0]->addChild('afa-e-mailadres', $this->customer->eMailadres);
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

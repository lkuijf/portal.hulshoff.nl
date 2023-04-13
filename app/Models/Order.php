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

    public function generateXml() {
        $aAdressen = [];
        $aDetails = [];
        $orderXml = new \SimpleXMLElement('<bericht></bericht>');
        $orderXml->addChild('bericht-type', 'IFC-ORDER-IN');
        $orderXml->addChild('bericht-id', $this->id);
        $orders = $orderXml->addChild('orders');
        $order = $orders->addChild('order');
        $order->addChild('ord-klant-code', ($this->hulshoffUser?$this->hulshoffUser->klantCode:'-deleted user-'));
        $order->addChild('ord-order-code-klant', $this->orderCodeKlant);
        $order->addChild('ord-order-code-aflever', $this->orderCodeAflever);
        $order->addChild('ord-eta-aflever-datum', $this->afleverDatum);
        $order->addChild('ord-eta-aflever-tijd', $this->afleverTijd);
        $adressen = $order->addChild('adressen');

        // if($this->hulshoffUser->customer) { // when the user is connected to a customer
        //     $aAdressen[0] = $adressen->addChild('adres');
        //     $aAdressen[0]->addChild('afa-afleveradres-code', 'ADRES1'); // ??
        //     $aAdressen[0]->addChild('afa-naam', $this->hulshoffUser->customer->straat);
        //     $aAdressen[0]->addChild('afa-straat', $this->hulshoffUser->customer->straat);
        //     $aAdressen[0]->addChild('afa-huisnummer', $this->hulshoffUser->customer->huisnummer);
        //     $aAdressen[0]->addChild('afa-postcode', $this->hulshoffUser->customer->postcode);
        //     $aAdressen[0]->addChild('afa-plaats', $this->hulshoffUser->customer->plaats);
        //     $aAdressen[0]->addChild('afa-land-code', $this->hulshoffUser->customer->landCode);
        //     $aAdressen[0]->addChild('afa-contactpersoon', $this->hulshoffUser->customer->contactpersoon);
        //     $aAdressen[0]->addChild('afa-telefoon', $this->hulshoffUser->customer->telefoon);
        //     $aAdressen[0]->addChild('afa-e-mailadres', $this->hulshoffUser->customer->eMailadres);
        // }

        $details = $order->addChild('details');
        if(count($this->orderArticles)) {
            foreach($this->orderArticles as $i => $ordArt) {
                $aDetails[$i] = $details->addChild('detail');
                $aDetails[$i]->addChild('odt-klant-regel-code', '100'); // ??
                $aDetails[$i]->addChild('odt-artikel-code', $ordArt->product->artikelCode);
                $aDetails[$i]->addChild('odt-stuks-besteld', $ordArt->amount);
            }
        }

        $filename = 'order-' . $this->id . '-' . date("Ymd-His") . '.xml';
        $orderXml->asXML(Storage::disk('local_xml_order_out')->path($filename));

        return true;
    }
}

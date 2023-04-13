<?php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://edi.hulshoff.nl/api/accept/interface-wti/BerichtOrders',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<?xml version="1.0"?>
<bericht><bericht-type>IFC-ORDER-IN</bericht-type><bericht-id>1675769785</bericht-id><orders><order><ord-klant-code>55054</ord-klant-code><ord-order-code-klant>16044</ord-order-code-klant><ord-order-code-aflever>2507501</ord-order-code-aflever><ord-eta-afleveren-datum>20230218</ord-eta-afleveren-datum><ord-eta-afleveren-tijd>0000</ord-eta-afleveren-tijd><adressen><adres><afa-afleveradres-code>ALGEMEEN</afa-afleveradres-code><afa-naam>Johan Dokter</afa-naam><afa-straat>Spaklerweg 4</afa-straat><afa-huisnummer/><afa-postcode/><afa-plaats/><afa-land-code>NL</afa-land-code><afa-contactpersoon>Johan Dokter</afa-contactpersoon><afa-telefoon>0205243767</afa-telefoon><afa-e-mailadres>j.a.dokter@dnb.nl</afa-e-mailadres></adres></adressen><details><detail><odt-klant-regel-code>16044</odt-klant-regel-code><odt-artikel-code>00011</odt-artikel-code><odt-stuks-besteld>1</odt-stuks-besteld></detail></details></order></orders></bericht>',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/xml'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
<?php

/**
* Dasturchi: Sattorbek Sag`dullayev.
* Dasturlash tili: php (asosan: 7.4+ versiya)
* Tuzilgan sana: 02.04.2023 (Oʻzbekiston vaqti bilan: 11:00AM);
* Ishlatish boʻyicha qoʻllanma: readme.txt
* 🛑 Mualliflik huquqi mavjud (OʻzRes Kon 38-modda)
**/

class Payme {

    private $card = '';

    public function __construct($card) {
        $this->_card = $card;
    }

    public function createPayment($amount = 0, $description = null, $back = null) {
    $amount = $amount * 100;
    $headers = array();
    $headers[] = 'device: 6Fk1rB';
    $headers[] = 'user-agent: Mozilla/57.36';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://payme.uz/api/p2p.create');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);       
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"method":"p2p.create","params":{"card_id":"'.$this->_card.'","amount":'.$amount.',"back": "'.$back.'","description":"'.$description.'"}}');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch), true);
    if (! empty ($response['result'])) {
        $json = [];
        $json['ok'] = true;
        $json['result']['id'] = $response['result']['cheque']['_id'];
        $json['result']['amount'] = $amount / 100;
        $json['result']['pay_url'] = "https://checkout.paycom.uz/{$response['result']['cheque']['_id']}";
    } else {
        $json = [];
        $json['ok'] = false;
        $json['error'] = $response['error'];
    }
    return json_encode($json, 64 | 128 | 256);
 }
 
    public function checkPayment($payment_id) {
    $headers = array();
    $headers[] = 'device: 6Fk1rB';
    $headers[] = 'user-agent: Mozilla/57.36';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://payme.uz/api/cheque.get');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);       
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"method":"cheque.get","params":{"id": '.$payment_id.'}}');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch), true);
    if (! empty ($response['result']['cheque']['pay_time'])) {
        $json = [];
        $json['ok'] = true;
        $json['payment']['pay_time'] = $response['result']['cheque']['pay_time'];
        $json['payment']['status'] = 'successfully';
    } else {
        $json = [];
        $json['ok'] = false;
        $json['payment']['status'] = 'unsuccessfully';
    }
    return json_encode($json, 64 | 128 | 256);
 }
}


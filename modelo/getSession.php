<?php

include('env.php');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email=gabrielsouzaweb@gmail.com&token=4F85DE139E4349F8AB2532E3791F17BF');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, true);

$data = curl_exec($ch);
$xml = new SimpleXMLElement($data);

echo $xml->id;
curl_close($ch);
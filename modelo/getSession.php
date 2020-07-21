<?php

include('env.php');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://'.urldecode($_GET['url']));


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, true);

$data = curl_exec($ch);
$xml = new SimpleXMLElement($data);

echo $xml->id;
curl_close($ch);
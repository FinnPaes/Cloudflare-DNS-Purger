<?php
// This is a plain PHP script to delete your Cloudflare DNS records.
// By Finn Paes April 20 - 2022

// Define your cloudflare auth/api key, cloudflare account mail, and zone id
define("auth_key", "XXX");
define("account_mail", "XXX");
define("zone_id", "XXX");

$method = "GET";
$url = 'https://api.cloudflare.com/client/v4/zones/'.constant("zone_id").'/dns_records';
$response = doCurlRequest($url, $method);

$res = json_decode($response);
echo $res->result_info->total_count."\n";
do{
    foreach($res->result as $r){
        echo $r->id."\n";
        $method = "DELETE";
        $url = 'https://api.cloudflare.com/client/v4/zones/'.constant("zone_id").'/dns_records/'.$r->id;
        $response = doCurlRequest($url, $method);
    }

    $method = "GET";
    $url = 'https://api.cloudflare.com/client/v4/zones/'.constant("zone_id").'/dns_records';
    $response = doCurlRequest($url, $method);

    $res = json_decode($response);
    echo $res->result_info->total_count."\n";
} while($res->result_info->total_count > 0);



function doCurlRequest($_url, $method) {
    $curl = curl_init($_url);
    curl_setopt($curl, CURLOPT_URL, $_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        'X-Auth-Key: '.constant("auth_key"),
        'X-Auth-Email: '.constant("account_mail"),
        'Content-Type: application/json'
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

?>

<?php

namespace App\util;

class Connection {

 public static function CURL($method, $url, $timeout = 60){
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL =>  $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => $timeout,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
    ]);

    $response = curl_exec($curl);

     curl_close($curl);

      if ($response === false) {
      return ['error' => curl_error($curl)];
    } else {
      return json_decode($response, true);
    }
   }
}
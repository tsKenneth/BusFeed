
<!DOCTYPE html>
<html>
<body>

<?php

require __DIR__."/vendor/autoload.php";
use GuzzleHttp\Client;

APIBusService();

function APIBusArr($busstopcode, $serviceno){
  //Authentication Parameters
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];

  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);

  if($serviceno == 0){
    $path = "ltaodataservice/BusArrivalv2?BusStopCode=".$busstopcode;
  }
  else{
    $path = "ltaodataservice/BusArrivalv2?BusStopCode=".$busstopcode."&ServiceNo=".$serviceno;
  }

  $response = $client->request('GET', $path, [
    'headers' => $headers,
  ]);

  $contents = $response->getBody()->getContents();
  echo $contents;
}

function APIBusService(){
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];

  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);
  $path = "ltaodataservice/BusStops";
  $response = $client->request('GET', $path, [
    'headers' => $headers,
  ]);
  $contents = $response->getBody()->getContents();
  echo $contents;
}

function APIBusRoutes(){
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];

  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);
  $path = "ltaodataservice/BusRoutes";
  $response = $client->request('GET', $path, [
    'headers' => $headers,
  ]);
  $contents = $response->getBody()->getContents();
  echo $contents;
}

function APIBusStops(){
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];

  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);
  $path = "ltaodataservice/BusStops";
  $response = $client->request('GET', $path, [
    'headers' => $headers,
  ]);
  $contents = $response->getBody()->getContents();
  echo $contents;
}

?>

</body>
</html>

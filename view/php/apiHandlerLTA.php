
<!DOCTYPE html>
<html>
<body>

<?php

require __DIR__."/vendor/autoload.php";
use GuzzleHttp\Client;


// gets the Bus Arrival Information from LTA DataMall
// serviceno is optional. If serviceno == 0, retrieve information about all the bus services at the busstop
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
  return $contents;
}

//gets all the Bus Services from LTA DataMall
function APIBusService(){
  $skip = 0;
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];
  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);
  $contentArray = [];
  while($skip < 2000){
    if ($skip == 0){
      $path = "ltaodataservice/BusServices";
    }
    else{
      $path = "ltaodataservice/BusServices?\$skip=".$skip;
    }
    $response = $client->request('GET', $path, [
      'headers' => $headers,
    ]);
    $tempcontent = $response->getBody()->getContents();
    array_push($contentArray, $tempcontent);
    $skip = $skip + 500;
  }

  return $contentArray;
}

//gets all the Bus Routes from LTA DataMall
function APIBusRoutes(){
  $skip = 0;
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];

  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);
  $contentArray = [];
  while($skip < 60000){
    if ($skip == 0){
      $path = "ltaodataservice/BusRoutes";
    }
    else{
      $path = "ltaodataservice/BusRoutes?\$skip=".$skip;
    }
    $response = $client->request('GET', $path, [
      'headers' => $headers,
    ]);
    $tempcontent = $response->getBody()->getContents();
    array_push($contentArray, $tempcontent);
    $skip = $skip + 500;
  }

  return $contentArray;
}


// gets all the Bus Stops from LTA DataMall
function APIBusStops(){
  $skip = 0;
  $headers = [
      "AccountKey" => "+yuVbNT4QnOGQw5ALCTntA==",
      "accept" => "application/json"
  ];
  $url = "http://datamall2.mytransport.sg/";
  $client = new Client([
    'base_uri' => $url
  ]);
  $contentArray = [];
  while($skip < 6000){
    if ($skip == 0){
      $path = "ltaodataservice/BusStops";
    }
    else{
      $path = "ltaodataservice/BusStops?\$skip=".$skip;
    }
    $response = $client->request('GET', $path, [
      'headers' => $headers,
    ]);
    $tempcontent = $response->getBody()->getContents();
    array_push($contentArray, $tempcontent);
    $skip = $skip + 500;
  }

  return $contentArray;
}

?>

</body>
</html>

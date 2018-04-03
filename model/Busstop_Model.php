<?php

include('../view/php/apiHandlerLTA.php');
include('datamapper/sqlite_mapper.php');

class Busstop {
    // Properties
    private $busStopCode;
    private $roadName;
    private $description;
    private $latitude;
    private $longitude;

    // Methods


    // Accessors and Mutators

    // Returns all information in array format
    public function getInformationArray() {

    }

    // Returns Coordinates in array format
    public function getCoordinatesArray() {

    }

    public function getBusstopCode() {

    }

    public function getRoadName() {

    }

    public function getDescription() {

    }

    public function getLatitude() {

    }

    public function getLongitude() {

    }
}

//Creates a Busstop table and stores all the Busstops from LTA DataMall to database
function storeBusstops(){
  $mapper = new SQLite_Mapper();

  $mapper->createBusStopTable();
  $busStopChunks = APIBusStops();
  $tempArray = array();
  $iter = 0;
  foreach ($busStopChunks as $busStopChunk){
    $Busstops = (json_decode($busStopChunk))->value;
    foreach ($Busstops as $Busstop) {
      $tempArray[$iter][0] = $Busstop->BusStopCode;
      $tempArray[$iter][1] = $Busstop->RoadName;
      $tempArray[$iter][2] = $Busstop->Longitude;
      $tempArray[$iter][3] = $Busstop->Latitude;
      $tempArray[$iter][4] = $Busstop->Description;
      $iter = $iter + 1;
    }
  }
  $mapper->addMultBusstop($tempArray);
}

?>

<?php

require_once('DomainObjectAbstract.php');

class Busstop extends DomainObjectAbstract{

    // Properties
    private $busStopCode;
    private $roadName;
    private $description;
    private $latitude;
    private $longitude;
    // Methods

    // Returns all information in array format
    public function getInformationArray() {

    }

    // Returns Coordinates in array format
    public function getCoordinatesArray() {

    }


    // Mutators

    public function setBusstopCode($info){
      $this->busStopCode = $info;
    }

    public function setRoadName($info){
      $this->roadName = $info;
    }

    public function setDescription($info){
      $this->description = $info;
    }

    public function setLatitude($info){
      $this->latitude = $info;
    }

    public function setLongitude($info){
      $this->longitude = $info;
    }

    // Accessors

    public function getBusstopCode() {
      return $this->busStopCode;

    }

    public function getRoadName() {
      return $this->roadName;
    }

    public function getDescription() {
      return $this->description;
    }

    public function getLatitude() {
      return $this->latitude;
    }

    public function getLongitude() {
      return $this->longitude;
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

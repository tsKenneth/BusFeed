<?php


include('../view/php/apiHandlerLTA.php');
include('datamapper/sqlite_mapper.php');


class Busroute {
    // Properties
    private $serviceNo;
    private $operator;
    private $direction;
    private $stopSequence;
    private $busStopCode;
    private $distance;
    private $WD_FirstBus;
    private $WD_LastBus;
    private $SAT_FirstBus;
    private $SAT_LastBus;
    private $SUN_FirstBus;
    private $SUN_LastBus;

    // Methods

    // Accessors and Mutators

    // Returns all information in array format
    public function getInformationArray() {

    }

    public function getServiceNo() {

    }

    public function getOperator(){

    }

    public function getDirection() {

    }

    public function getStopSequence() {

    }

    public function getBusstopCode() {

    }

    public function getDistance() {

    }

    public function getWD_FirstBus() {

    }

    public function getWD_LastBus() {

    }

    public function getSAT_FirstBus() {

    }

    public function getSAT_LastBus() {

    }

    public function getSUN_FirstBus() {

    }

    public function getSUN_LastBus() {

    }

}

function storeBusRoutes(){
  $mapper = new SQLite_Mapper();
  $mapper->createBusRouteTable();
  $busRouteChunks = APIBusRoutes();
  $tempArray = array();
  $iter = 0;
  foreach ($busRouteChunks as $busRouteChunk){
    $BusRoutes = (json_decode($busRouteChunk))->value;
    foreach ($BusRoutes as $BusRoute) {
      $tempArray[$iter][0] = $BusRoute->ServiceNo;
      $tempArray[$iter][1] = $BusRoute->Operator;
      $tempArray[$iter][2] = $BusRoute->Direction;
      $tempArray[$iter][3] = $BusRoute->StopSequence;
      $tempArray[$iter][4] = $BusRoute->BusStopCode;
      $tempArray[$iter][5] = $BusRoute->Distance;
      $tempArray[$iter][6] = $BusRoute->WD_FirstBus;
      $tempArray[$iter][7] = $BusRoute->WD_LastBus;
      $tempArray[$iter][8] = $BusRoute->SAT_FirstBus;
      $tempArray[$iter][9] = $BusRoute->SAT_LastBus;
      $tempArray[$iter][10] = $BusRoute->SUN_FirstBus;
      $tempArray[$iter][11] = $BusRoute->SUN_LastBus;
      $iter = $iter + 1;
    }
  }
  $mapper->addMultBusRoute($tempArray);
}

?>

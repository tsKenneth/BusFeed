<?php


include('../view/php/apiHandlerLTA.php');
include('datamapper/sqlite_mapper.php');



class Busservice {



    // Properties
    private $serviceNo;
    private $operator;
    private $direction;
    private $category;
    private $originCode;
    private $destinationCode;
    private $AM_Peak_Freq;
    private $AM_Offpeak_Freq;
    private $PM_Peak_Freq;
    private $PM_Offpeak_Freq;
    private $loopDesc;

    // Methods



    // Accessors and Mutators

    // Returns all information in array format
    public function getInformationArray() {

    }

    public function getSeviceNo() {

    }

    public function getOperator() {

    }

    public function getDirection() {

    }

    public function getCategory() {

    }

    public function getOriginCode() {

    }

    public function getDestinationCode() {

    }

    public function getAMDestinationCode() {

    }

    public function getAM_Offpeak_Freq() {

    }

    public function getPM_Peak_Freq() {

    }

    public function getPM_Offpeak_Freq() {

    }

    public function getLoopDesc() {

    }
}

//Creates a Busstop table and stores all the Busstops from LTA DataMall to database
function storeBusServices(){
  $mapper = new SQLite_Mapper();
  $mapper->createBusServiceTable();
  $busServiceChunks = APIBusService();
  $tempArray = array();
  $iter = 0;
  foreach ($busServiceChunks as $busServiceChunk){
    $BusServices = (json_decode($busServiceChunk))->value;
    foreach ($BusServices as $BusService) {
      $tempArray[$iter][0] = $BusService->ServiceNo;
      $tempArray[$iter][1] = $BusService->Operator;
      $tempArray[$iter][2] = $BusService->Direction;
      $tempArray[$iter][3] = $BusService->Category;
      $tempArray[$iter][4] = $BusService->OriginCode;
      $tempArray[$iter][5] = $BusService->DestinationCode;
      $tempArray[$iter][6] = $BusService->AM_Peak_Freq;
      $tempArray[$iter][7] = $BusService->AM_Offpeak_Freq;
      $tempArray[$iter][8] = $BusService->PM_Peak_Freq;
      $tempArray[$iter][9] = $BusService->PM_Offpeak_Freq;
      $tempArray[$iter][10] = $BusService->LoopDesc;
      $iter = $iter + 1;
    }
  }
  $mapper->addMultBusservice($tempArray);
}

?>

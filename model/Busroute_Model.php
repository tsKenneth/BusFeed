<?php
require_once('DomainObjectAbstract.php');
require_once('Busarrival_Model.php');
require_once('datamapper/sqlite/BusrouteMapper_sqlite.php');
require_once('../view/php/apiHandlerLTA.php');


class Busroute extends DomainObjectAbstract {

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

    public static function retrieveBusroute($serviceNo, $direction, $busStopCode) {
        $mapper = new BusrouteMapper_sqlite();
        return $mapper->getRouteStop($serviceNo, $direction, $busStopCode);
    }

    public static function retrieveRouteFromBusstop($busStopCode) {
        $mapper = new BusrouteMapper_sqlite();
        return $mapper->getRoutesFromBusStop($busStopCode);
    }

    public static function retrieveAllBusRoute($serviceNo, $direction) {
        $mapper = new BusrouteMapper_sqlite();
        return $mapper->getRouteFull($serviceNo, $direction);
    }

    // Methods

    public function JsonSerialize(){
      return [
            'serviceNo' => $this->getServiceNo(),
            'operator' => $this->getOperator(),
            'direction' => $this->getDirection(),
            'stopSequence' => $this->getStopSequence(),
            'busStopCode' => $this->getBusStopCode(),
            'distance' => $this->getDistance(),
            'WD_FirstBus' => $this->getWD_FirstBus(),
            'WD_LastBus' => $this->getWD_LastBus(),
            'SAT_FirstBus' => $this->getSAT_FirstBus(),
            'SAT_LastBus' => $this->getSAT_LastBus(),
            'SUN_FirstBus' => $this->getSUN_FirstBus(),
            'SUN_LastBus' => $this->getSUN_LastBus()
        ];
    }

    // Returns a size 3 array of Bus Arrival objects with the following format (NextBus, NextBus2, NextBus3)
    public function getArrivalTimingsAPI(){
      $sNo = $this->getServiceNo();
      $bscode = $this->getBusStopCode();

      $arrival1 = new Busarrival();
      $arrival2 = new Busarrival();
      $arrival3 = new Busarrival();

      $json = APIBusArrival($bscode,$sNo);

      $contentArr = (json_decode($json))->Services;
      $content = $contentArr[0];

      $arrivalArr = array($arrival1,$arrival2,$arrival3);
      $nextBusArr = array('NextBus', 'NextBus2', 'NextBus3');
      $iter = 0;

      foreach($nextBusArr as $nextBus){
        $arrivalArr[$iter]->setOriginCode($content->$nextBus->OriginCode);
        $arrivalArr[$iter]->setDestinationCode($content->$nextBus->DestinationCode);
        $arrivalArr[$iter]->setEstimatedArrival($content->$nextBus->EstimatedArrival);
        $arrivalArr[$iter]->setLatitude($content->$nextBus->Latitude);
        $arrivalArr[$iter]->setLongitude($content->$nextBus->Longitude);
        $arrivalArr[$iter]->setVisitNumber($content->$nextBus->VisitNumber);
        $arrivalArr[$iter]->setLoad($content->$nextBus->Load);
        $arrivalArr[$iter]->setFeature($content->$nextBus->Feature);
        $arrivalArr[$iter]->setType($content->$nextBus->Type);
        $arrivalArr[$iter]->setArrivalMinute($content->$nextBus->EstimatedArrival);
        $iter = $iter + 1;
      }
      array_push($arrivalArr,$sNo);
      return $arrivalArr;
    }


    //Mutators
    public function setServiceNo($info) {
      $this->serviceNo = $info;
    }

    public function setOperator($info){
      $this->operator = $info;
    }

    public function setDirection($info) {
      $this->direction = $info;
    }

    public function setStopSequence($info) {
      $this->stopSequence = $info;
    }

    public function setBusStopCode($info) {
      $this->busStopCode = $info;
    }

    public function setDistance($info) {
      $this->distance = $info;
    }

    public function setWD_FirstBus($info) {
      $this->WD_FirstBus = $info;
    }

    public function setWD_LastBus($info) {
      $this->WD_LastBus = $info;
    }

    public function setSAT_FirstBus($info) {
      $this->SAT_FirstBus = $info;
    }

    public function setSAT_LastBus($info) {
      $this->SAT_LastBus = $info;
    }

    public function setSUN_FirstBus($info) {
      $this->SUN_FirstBus = $info;
    }

    public function setSUN_LastBus($info) {
      $this->SUN_LastBus = $info;
    }

    public function setNextBusArrival($info){
      $this->nextBusArrival = $info;
    }

    public function setNextBusArrival2($info){
      $this->nextBusArrival2 = $info;
    }

    public function setNextBusArrival3($info){
      $this->nextBusArrival3 = $info;
    }

    //Accessors
    public function getServiceNo() {
      return $this->serviceNo;
    }

    public function getOperator(){
      return $this->operator;
    }

    public function getDirection() {
      return $this->direction;
    }

    public function getStopSequence() {
      return $this->stopSequence;
    }

    public function getBusstopCode() {
      return $this->busStopCode;
    }

    public function getDistance() {
      return $this->distance;
    }

    public function getWD_FirstBus() {
      return $this->WD_FirstBus;
    }

    public function getWD_LastBus() {
      return $this->WD_LastBus;
    }

    public function getSAT_FirstBus() {
      return $this->SAT_FirstBus;
    }

    public function getSAT_LastBus() {
      return $this->SAT_LastBus;
    }

    public function getSUN_FirstBus() {
      return $this->SUN_FirstBus;
    }

    public function getSUN_LastBus() {
      return $this->SUN_LastBus;
    }

    public function getNextBusArrival(){
      return $this->nextBusArrival;
    }

    public function getNextBusArrival2(){
      return $this->nextBusArrival2;
    }

    public function getNextBusArrival3(){
      return $this->nextBusArrival3;
    }
}
?>

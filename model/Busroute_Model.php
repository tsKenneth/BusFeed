<?php


require_once('DomainObjectAbstract.php');


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

    // Methods

    // Returns all information in array format
    public function getInformationArray() {

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

}


?>

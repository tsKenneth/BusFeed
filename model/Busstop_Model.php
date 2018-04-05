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

?>

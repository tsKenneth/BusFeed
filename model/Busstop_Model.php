<?php
require_once('DomainObjectAbstract.php');
require_once('datamapper/sqlite/BusstopMapper_sqlite.php');

class Busstop extends DomainObjectAbstract{

    // Properties
    private $busStopCode;
    private $roadName;
    private $description;
    private $latitude;
    private $longitude;

    public function retrieveBusstop($busStopCode) {
        $mapper = new BusstopMapper_sqlite();
        return $mapper->getByBusStopCode($busStopCode);
    }

    public function retrieveAllBusstops() {
        $mapper = new BusstopMapper_sqlite();
        return $mapper->getAllBusstop();
    }

    public function retrieveNearbyBusstops($cLong,$cLat,$maxdist) {
        $mapper = new BusstopMapper_sqlite();
        return $mapper->getNearestBusstop($cLong,$cLat,$maxdist);
    }

    // Methods
    public function JsonSerialize(){
      return [
            'busStopCode' => $this->getBusStopCode(),
            'roadName' => $this->getRoadName(),
            'description' => $this->getDescription(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
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

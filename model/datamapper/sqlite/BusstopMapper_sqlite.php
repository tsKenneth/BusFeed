<?php

require_once('MapperAbstract_sqlite.php');
require_once(__DIR__.'/../../Busstop_Model.php');


class BusstopMapper_sqlite extends MapperAbstract_sqlite{

    public function __construct(){

    }

    // Creates new Bus Stop Table
    public function createTable(){
      $db = new DB();
      if(!$db) {
         echo $db->lastErrorMsg();
      }
      $command = 'CREATE TABLE IF NOT EXISTS busstops (
                    busstopcode  INTEGER PRIMARY KEY,
                    roadname TEXT NOT NULL,
                    latitude NUMERIC NOT NULL,
                    longitude NUMERIC NOT NULL,
                    description TEXT
                  );';

      $db->exec($command);
      $db->close();
    }


    // populates the object with their respective values
    // Bus Stop id is BS followed by busstopcode (eg. BS5051)
    public function populate(DomainObjectAbstract $obj, array $data){

      $obj->setId('BS'.strval($data['busstopcode']));
      $obj->setBusstopCode($data['busstopcode']);
      $obj->setRoadName($data['roadname']);
      $obj->setLatitude($data['latitude']);
      $obj->setLongitude($data['longitude']);
      $obj->setDescription($data['description']);

      return $obj;
    }

    // stores bus stops from API
    function storeFromAPI(){

      $blocks = APIBusStops();
      $bsArr = array();
      $iter = 0;

      foreach ($blocks as $block){
        $Busstops = (json_decode($block))->value;
        foreach ($Busstops as $Busstop) {
          $infoArr = array();
          $infoArr['busstopcode'] = $Busstop->BusStopCode;
          $infoArr['roadname'] = $Busstop->RoadName;
          $infoArr['longitude'] = $Busstop->Longitude;
          $infoArr['latitude'] = $Busstop->Latitude;
          $infoArr['description'] = $Busstop->Description;
          $bsArr[$iter] = $this->create($infoArr);
          $iter = $iter + 1;
        }
      }
      $this->_insertMultiple($bsArr);
      return;
    }

    // find a bus stop and creates new bus stop object
    // returns a Bus Stop object
    public function getByBusStopCode($busStopCode){
      $db = new DB();
      $bscode = $busStopCode;

      $sql = "SELECT busstopcode, roadname, longitude, latitude, description
              FROM busstops
              WHERE busstopcode = :bscode;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_TEXT);

      $res = $stmt->execute();
      $row = $res->fetchArray(SQLITE3_ASSOC);

      $db->close();
      if($row){
        return $this->create($row);
      }
      else {
        echo('Row does not exist in database');
      }
    }

    // gets all the bus stops from the database
    // returns an array of Bus Stop objects
    public function getAllBusstop(){
      $db = new DB();
      $bsArr = array();
      $iter = 0;

      $sql = "SELECT busstopcode, roadname, longitude, latitude, description
              FROM busstops;";
      $stmt = $db->prepare($sql);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }
      foreach($rowArray as $data){
        $bsArr[$iter] = $this->create($data);
        $iter = $iter + 1;
      }

      $db->close();
      return $bsArr;
    }

    // gets all nearest bus stops based on center long and center lat coordinates. max dist is in km
    // returns an array of Bus Stop objects
    public function getNearestBusstop($cLong,$cLat,$maxdist){
      $db = new DB();
      $bsArr = array();
      $iter = 0;

      $db->createFunction('haversine',   function($long,$lat,$cLong,$cLat){
                                            $deltaLat = deg2rad($lat - $cLat);
                                            $deltaLong = deg2rad($long - $cLong);
                                            $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
                                               cos(deg2rad($cLat)) * cos(deg2rad($lat)) *
                                               sin($deltaLong / 2) * sin($deltaLong / 2);
                                            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                                            return 6371 * $c;
                                          }, 4);
      $sql = "SELECT *,
              haversine(longitude, latitude, :cLong, :cLat) AS distance
              FROM busstops
              WHERE distance < :maxdist;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':cLong', $cLong, SQLITE3_FLOAT);
      $stmt->bindValue(':cLat', $cLat, SQLITE3_FLOAT);
      $stmt->bindValue(':maxdist', $maxdist, SQLITE3_FLOAT);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }
      foreach($rowArray as $data){
        $bsArr[$iter] = $this->create($data);
        $iter = $iter + 1;
      }

      $db->close();
      return $bsArr;
    }

    // functions below can only be called by this class

    protected function _create(){
        return new Busstop();

    }

    // receives a Bus Stop object
    protected function _insert(DomainObjectAbstract $obj){
      $db = new DB();

      $bscode = $obj->getBusstopCode();
      $roadname = $obj->getRoadName();
      $long = $obj->getLogitude();
      $lat = $obj->getLatitude();
      $des = $obj->getDescription();
      $sql = "INSERT INTO busstops(busstopcode, roadname, longitude, latitude, description)
              VALUES(:bscode, :roadname, :long, :lat, :des);";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
      $stmt->bindValue(':roadname', $roadname, SQLITE3_TEXT);
      $stmt->bindValue(':long', $long, SQLITE3_FLOAT);
      $stmt->bindValue(':lat', $lat, SQLITE3_FLOAT);
      $stmt->bindValue(':des', $des, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // receives an array of Bus Stop objects
    protected function _insertMultiple(array $bsArr){
      $db = new DB();

      foreach($bsArr as $bs){
        $bscode = $bs->getBusstopCode();
        $roadname = $bs->getRoadName();
        $long = $bs->getLongitude();
        $lat = $bs->getLatitude();
        $des = $bs->getDescription();
        $sql = "INSERT INTO busstops(busstopcode, roadname, longitude, latitude, description)
                VALUES(:bscode, :roadname, :long, :lat, :des);";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
        $stmt->bindValue(':roadname', $roadname, SQLITE3_TEXT);
        $stmt->bindValue(':long', $long, SQLITE3_FLOAT);
        $stmt->bindValue(':lat', $lat, SQLITE3_FLOAT);
        $stmt->bindValue(':des', $des, SQLITE3_TEXT);
        $stmt->execute();
      }

      $db->close();
      return;
    }

    // updates the row in the database by passing in a Bus Stop object with the desired bus stop code
    protected function _update(DomainObjectAbstract $obj){
      $db = new DB();

      $bscode = $obj->getBusstopCode();
      $roadname = $obj->getRoadName();
      $long = $obj->getLogitude();
      $lat = $obj->getLatitude();
      $des = $$obj->getDescription();
      $sql = "UPDATE busstops
              SET roadname=:roadname, longitude=:long, latitude=:lat, description=:des
              WHERE busstopcode = :bscode;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
      $stmt->bindValue(':roadname', $roadname, SQLITE3_TEXT);
      $stmt->bindValue(':long', $long, SQLITE3_FLOAT);
      $stmt->bindValue(':lat', $lat, SQLITE3_FLOAT);
      $stmt->bindValue(':des', $des, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // remove the row in the database
    protected function _delete(DomainObjectAbstract $obj){
      $db = new DB();
      $bscode = $obj->getBusstopCode();

      $sql = "DELETE FROM busstops
              WHERE busstopcode = :bscode;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
      $stmt->execute();

      $db->close();
      return;
    }





}



?>

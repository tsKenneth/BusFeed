<?php

require_once('MapperAbstract_sqlite.php');
require_once(__DIR__.'/../../Busroute_Model.php');


class BusrouteMapper_sqlite extends MapperAbstract_sqlite{

    public function __construct(){

    }

    // Creates new Bus Service Table
    public function createTable(){
      $db = new DB();
      if(!$db) {
         echo $db->lastErrorMsg();
      }
      $command = 'CREATE TABLE IF NOT EXISTS busroutes(
                    serviceno TEXT NOT NULL,
                    operator TEXT,
                    direction INTEGER NOT NULL,
                    stopsequence INTEGER NOT NULL,
                    busstopcode INTEGER NOT NULL,
                    distance NUMERIC,
                    wdfirstbus TEXT,
                    wdlastbus TEXT,
                    satfirstbus TEXT,
                    satlastbus TEXT,
                    sunfirstbus TEXT,
                    sunlastbus TEXT,
                    PRIMARY KEY(serviceno, direction, busstopcode)
                    FOREIGN KEY(serviceno) REFERENCES busservices(serviceno) ON UPDATE CASCADE
                    FOREIGN KEY(direction) REFERENCES busservices(direction) ON UPDATE CASCADE
                    FOREIGN KEY(busstopcode) REFERENCES busstops(busstopcode) ON UPDATE CASCADE
                  );';
      $db->exec($command);
      $db->close();
    }

    // populates the object with their respective values
    // Bus Route id is is BR followed by serviceno, direction, and busstopcode (eg.BR188E15052)
    public function populate(DomainObjectAbstract $obj, array $data){

      $obj->setId('BR'.$data['serviceno'].strval($data['direction']).strval($data['busstopcode']));
      $obj->setServiceNo($data['serviceno']);
      $obj->setOperator($data['operator']);
      $obj->setDirection($data['direction']);
      $obj->setStopSequence($data['stopsequence']);
      $obj->setBusStopCode($data['busstopcode']);
      $obj->setDistance($data['distance']);
      $obj->setWD_FirstBus($data['wdfirstbus']);
      $obj->setWD_LastBus($data['wdlastbus']);
      $obj->setSAT_FirstBus($data['satfirstbus']);
      $obj->setSAT_LastBus($data['satlastbus']);
      $obj->setSUN_FirstBus($data['sunfirstbus']);
      $obj->setSUN_LastBus($data['sunlastbus']);

      return $obj;
    }

    // stores bus routes from API
    function storeFromAPI(){

      $blocks = APIBusRoutes();
      $brArr = array();
      $iter = 0;

      foreach ($blocks as $block){
        $BusRoutes = (json_decode($block))->value;
        foreach ($BusRoutes as $BusRoute) {
          $infoArr = array();
          $infoArr['serviceno'] = $BusRoute->ServiceNo;
          $infoArr['operator'] = $BusRoute->Operator;
          $infoArr['direction'] = $BusRoute->Direction;
          $infoArr['stopsequence'] = $BusRoute->StopSequence;
          $infoArr['busstopcode'] = $BusRoute->BusStopCode;
          $infoArr['distance'] = $BusRoute->Distance;
          $infoArr['wdfirstbus'] = $BusRoute->WD_FirstBus;
          $infoArr['wdlastbus'] = $BusRoute->WD_LastBus;
          $infoArr['satfirstbus'] = $BusRoute->SAT_FirstBus;
          $infoArr['satlastbus'] = $BusRoute->SAT_LastBus;
          $infoArr['sunfirstbus'] = $BusRoute->SUN_FirstBus;
          $infoArr['sunlastbus'] = $BusRoute->SUN_LastBus;
          $brArr[$iter] = $this->create($infoArr);
          $iter = $iter + 1;
        }
      }
      $this->_insertMultiple($brArr);
      return;
    }

    // find a bus route stop by its serviceNo, direction and bus stop code
    // returns a Bus Route object
    public function getRouteStop($serviceNo, $direction, $busStopCode){
      $db = new DB();
      $sNo = $serviceNo;
      $dir = $direction;
      $bscode = $busStopCode;

      $sql = "SELECT serviceno, operator, direction, stopsequence, busstopcode, distance, wdfirstbus, wdlastbus, satfirstbus, satlastbus, sunfirstbus, sunlastbus
              FROM busroutes
              WHERE serviceno = :sNo AND direction = :dir AND busstopcode = :bscode";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':sNo', $sNo, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
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

    // find all bus routes at a particular busstop
    // returns an array of Bus Route objects
    public function getRoutesFromBusStop($busStopCode){
      $db = new DB();
      $bscode = $busStopCode;
      $iter = 0;
      $brArr = array();

      $sql = "SELECT serviceno, operator, direction, stopsequence, busstopcode, distance, wdfirstbus, wdlastbus, satfirstbus, satlastbus, sunfirstbus, sunlastbus
              FROM busroutes
              WHERE busstopcode = :bscode";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }
      foreach($rowArray as $data){
        $brArr[$iter] = $this->create($data);
        $iter = $iter + 1;
      }

      $db->close();

      return $brArr;
    }

    // find a full bus route by its serviceNo and direction
    // returns a 2D array of Bus Route objects
    public function getRouteFull($serviceNo, $direction){
      $db = new DB();
      $sNo = $serviceNo;
      $dir = $direction;
      $iter = 0;
      $brArr = array();

      $sql = "SELECT serviceno, operator, direction, stopsequence, busstopcode, distance, wdfirstbus, wdlastbus, satfirstbus, satlastbus, sunfirstbus, sunlastbus
              FROM busroutes
              WHERE serviceno = :sNo AND direction = :dir;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':sNo', $sNo, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }
      foreach($rowArray as $data){
        $brArr[$iter] = $this->create($data);
        $iter = $iter + 1;
      }

      $db->close();
      return $brArr;
    }

    // functions below can only be called by this class

    protected function _create(){
        return new Busroute();

    }

    // receives a Bus Route object
    protected function _insert(DomainObjectAbstract $obj){
      $db = new DB();

      $serviceno = $obj->getServiceNo();
      $op = $obj->getOperator();
      $dir = $obj->getDirection();
      $stopseq = $obj->getStopSequence();
      $bscode = $obj->getByBusStopCode();
      $dist = $obj->getDistance();
      $wdFB = $obj->getWD_FirstBus();
      $wdLB = $obj->getWD_LastBus();
      $satFB = $obj->getSAT_FirstBus();
      $satLB = $obj->getSAT_LastBus();
      $sunFB = $obj->getSUN_FirstBus();
      $sunLB = $obj->getSUN_LastBus();
      $sql = "INSERT INTO busroutes(serviceno, operator, direction, stopsequence, busstopcode, distance, wdfirstbus, wdlastbus, satfirstbus, satlastbus, sunfirstbus, sunlastbus)
              VALUES(:serviceno, :op, :dir, :stopseq, :bscode, :dist, :wdFB, :wdLB, :satFB, :satLB, :sunFB, :sunLB);";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':serviceno', $serviceno, SQLITE3_TEXT);
      $stmt->bindValue(':op', $op, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->bindValue(':stopseq', $stopseq, SQLITE3_INTEGER);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
      $stmt->bindValue(':dist', $dist, SQLITE3_FLOAT);
      $stmt->bindValue(':wdFB', $wdFB, SQLITE3_TEXT);
      $stmt->bindValue(':wdLB', $wdLB, SQLITE3_TEXT);
      $stmt->bindValue(':satFB', $satFB, SQLITE3_TEXT);
      $stmt->bindValue(':satLB', $satLB, SQLITE3_TEXT);
      $stmt->bindValue(':sunFB', $sunFB, SQLITE3_TEXT);
      $stmt->bindValue(':sunLB', $sunLB, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // receives an array of Bus Route objects
    protected function _insertMultiple(array $brArr){
      $db = new DB();

      foreach($brArr as $br){
        $serviceno = $br->getServiceNo();
        $op = $br->getOperator();
        $dir = $br->getDirection();
        $stopseq = $br->getStopSequence();
        $bscode = $br->getBusStopCode();
        $dist = $br->getDistance();
        $wdFB = $br->getWD_FirstBus();
        $wdLB = $br->getWD_LastBus();
        $satFB = $br->getSAT_FirstBus();
        $satLB = $br->getSAT_LastBus();
        $sunFB = $br->getSUN_FirstBus();
        $sunLB = $br->getSUN_LastBus();
        $sql = "INSERT INTO busroutes(serviceno, operator, direction, stopsequence, busstopcode, distance, wdfirstbus, wdlastbus, satfirstbus, satlastbus, sunfirstbus, sunlastbus)
                VALUES(:serviceno, :op, :dir, :stopseq, :bscode, :dist, :wdFB, :wdLB, :satFB, :satLB, :sunFB, :sunLB);";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':serviceno', $serviceno, SQLITE3_TEXT);
        $stmt->bindValue(':op', $op, SQLITE3_TEXT);
        $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
        $stmt->bindValue(':stopseq', $stopseq, SQLITE3_INTEGER);
        $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
        $stmt->bindValue(':dist', $dist, SQLITE3_FLOAT);
        $stmt->bindValue(':wdFB', $wdFB, SQLITE3_TEXT);
        $stmt->bindValue(':wdLB', $wdLB, SQLITE3_TEXT);
        $stmt->bindValue(':satFB', $satFB, SQLITE3_TEXT);
        $stmt->bindValue(':satLB', $satLB, SQLITE3_TEXT);
        $stmt->bindValue(':sunFB', $sunFB, SQLITE3_TEXT);
        $stmt->bindValue(':sunLB', $sunLB, SQLITE3_TEXT);
        $stmt->execute();
      }

      $db->close();
      return;
    }

    // updates the row in the database by passing in a Bus Service object with the desired service no, direction and bus stop code
    protected function _update(DomainObjectAbstract $obj){
      $db = new DB();

      $serviceno = $obj->getServiceNo();
      $op = $obj->getOperator();
      $dir = $obj->getDirection();
      $stopseq = $obj->getStopSequence();
      $bscode = $obj->getByBusStopCode();
      $dist = $obj->getDistance();
      $wdFB = $obj->getWD_FirstBus();
      $wdLB = $obj->getWD_LastBus();
      $satFB = $obj->getSAT_FirstBus();
      $satLB = $obj->getSAT_LastBus();
      $sunFB = $obj->getSUN_FirstBus();
      $sunLB = $obj->getSUN_LastBus();
      $sql = "UPDATE busroutes
              SET operator = :op, stopsequence = :stopseq, distance = :dist, wdfirstbus = :wdFB, wdlastbus = :wdLB, satfirstbus = :satFB, satlastbus = :satLB, sunfirstbus = :sunFB, sunlastbus = :sunLB
              WHERE serviceno = :serviceno AND direction = :dir AND busstopcode = :bscode;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':serviceno', $serviceno, SQLITE3_TEXT);
      $stmt->bindValue(':op', $op, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->bindValue(':stopseq', $stopseq, SQLITE3_INTEGER);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_INTEGER);
      $stmt->bindValue(':dist', $dist, SQLITE3_FLOAT);
      $stmt->bindValue(':wdFB', $wdFB, SQLITE3_TEXT);
      $stmt->bindValue(':wdLB', $wdLB, SQLITE3_TEXT);
      $stmt->bindValue(':satFB', $satFB, SQLITE3_TEXT);
      $stmt->bindValue(':satLB', $satLB, SQLITE3_TEXT);
      $stmt->bindValue(':sunFB', $sunFB, SQLITE3_TEXT);
      $stmt->bindValue(':sunLB', $sunLB, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // remove the whole bus route (multiple rows)
    protected function _delete(DomainObjectAbstract $obj){
      $db = new DB();
      $sNo = $obj->getServiceNo();
      $dir = $obj->getDirection();

      $sql = "DELETE FROM busservices
              WHERE serviceno = :sNo AND direction = :dir;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':sNo', $sNo, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->execute();

      $db->close();
      return;
    }
}



?>

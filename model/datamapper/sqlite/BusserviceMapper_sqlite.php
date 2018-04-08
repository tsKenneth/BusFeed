<?php
require_once('MapperAbstract_sqlite.php');
require_once(__DIR__.'/../../Busservice_Model.php');


class BusserviceMapper_sqlite extends MapperAbstract_sqlite{

    public function __construct(){
    }

    // Creates new Bus Service Table
    public function createTable(){
      $db = new DB();
      if(!$db) {
         echo $db->lastErrorMsg();
      }
      $command = 'CREATE TABLE IF NOT EXISTS busservices(
                    serviceno TEXT NOT NULL,
                    direction INTEGER NOT NULL,
                    operator TEXT,
                    category TEXT,
                    origincode INTEGER NOT NULL,
                    destinationcode INTEGER NOT NULL,
                    ampeakfreq TEXT,
                    amoffpeakfreq TEXT,
                    pmpeakfreq TEXT,
                    pmoffpeakfreq TEXT,
                    loopdescription TEXT,
                    PRIMARY KEY(serviceno, direction)
                    FOREIGN KEY(origincode) REFERENCES busstops(busstopcode) ON UPDATE CASCADE
                    FOREIGN KEY(destinationcode) REFERENCES busstops(busstopcode) ON UPDATE CASCADE
                  )';
      $db->exec($command);
      $db->close();
    }

    // populates the object with their respective values
    // Bus Service id is is BE followed by serviceno and direction (eg.BE188E1)
    public function populate(DomainObjectAbstract $obj, array $data){

      $obj->setId('BE'.$data['serviceno'].strval($data['direction']));
      $obj->setServiceNo($data['serviceno']);
      $obj->setDirection($data['direction']);
      $obj->setOperator($data['operator']);
      $obj->setCategory($data['category']);
      $obj->setOriginCode($data['origincode']);
      $obj->setDestinationCode($data['destinationcode']);
      $obj->setAMP($data['ampeakfreq']);
      $obj->setAMO($data['amoffpeakfreq']);
      $obj->setPMP($data['pmpeakfreq']);
      $obj->setPMO($data['pmoffpeakfreq']);
      $obj->setLoopDescription($data['loopdescription']);

      return $obj;
    }

    // stores bus services from API
    function storeFromAPI(){

      $blocks = APIBusService();
      $beArr = array();
      $iter = 0;

      foreach ($blocks as $block){
        $BusServices = (json_decode($block))->value;
        foreach ($BusServices as $BusService) {
          $infoArr = array();
          $infoArr['serviceno'] = $BusService->ServiceNo;
          $infoArr['direction'] = $BusService->Direction;
          $infoArr['operator'] = $BusService->Operator;
          $infoArr['category'] = $BusService->Category;
          $infoArr['origincode'] = $BusService->OriginCode;
          $infoArr['destinationcode'] = $BusService->DestinationCode;
          $infoArr['ampeakfreq'] = $BusService->AM_Peak_Freq;
          $infoArr['amoffpeakfreq'] = $BusService->AM_Offpeak_Freq;
          $infoArr['pmpeakfreq'] = $BusService->PM_Peak_Freq;
          $infoArr['pmoffpeakfreq'] = $BusService->PM_Offpeak_Freq;
          $infoArr['loopdescription'] = $BusService->LoopDesc;
          $beArr[$iter] = $this->create($infoArr);
          $iter = $iter + 1;
        }
      }
      $this->_insertMultiple($beArr);
      return;
    }

    // find a bus service by its service no and direction
    // returns a Bus Service object
    public function getByServiceNoDirection($serviceNo,$direction){
      $db = new DB();
      $sNo = $serviceNo;
      $dir = $direction;

      $sql = "SELECT serviceno, operator, direction, category, origincode, destinationcode, ampeakfreq, amoffpeakfreq, pmpeakfreq, pmoffpeakfreq, loopdescription
              FROM busservices
              WHERE serviceno = :sNo AND direction = :dir;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':sNo', $sNo, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
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

    // gets all the bus service from the database
    // returns an array of Bus Service objects
    public function getAllBusservice(){
      $db = new DB();
      $beArr = array();
      $iter = 0;

      $sql = "SELECT serviceno, operator, direction, category, origincode, destinationcode, ampeakfreq, amoffpeakfreq, pmpeakfreq, pmoffpeakfreq, loopdescription
              FROM busservices;";
      $stmt = $db->prepare($sql);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }
      foreach($rowArray as $data){
        $beArr[$iter] = $this->create($data);
        $iter = $iter + 1;
      }

      $db->close();
      return $beArr;
    }

    // functions below can only be called by this class

    protected function _create(){
        return new Busservice();

    }

    // receives a Bus Service object
    protected function _insert(DomainObjectAbstract $obj){
      $db = new DB();

      $serviceno = $obj->getServiceNo();
      $op = $obj->getOperator();
      $dir = $obj->getDirection();
      $cat = $obj->getCategory();
      $oC = $obj->getOriginCode();
      $dC = $obj->getDestinationCode();
      $amPF = $obj->getAM_Peak_Freq();
      $amOPF = $obj->getAM_Offpeak_Freq();
      $pmPF = $obj->getPM_Peak_Freq();
      $pmOPF = $obj->getPM_Offpeak_Freq();
      $loopdes = $obj->getLoopDesc();
      $sql = "INSERT INTO busservices(serviceno, operator, direction, category, origincode, destinationcode, ampeakfreq, amoffpeakfreq, pmpeakfreq, pmoffpeakfreq, loopdescription)
              VALUES(:serviceno, :op, :dir, :cat, :oC, :dC, :amPF, :amOPF, :pmPF, :pmOPF, :loopdes);";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':serviceno', $serviceno, SQLITE3_TEXT);
      $stmt->bindValue(':op', $op, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->bindValue(':cat', $cat, SQLITE3_TEXT);
      $stmt->bindValue(':oC', $oC, SQLITE3_INTEGER);
      $stmt->bindValue(':dC', $dC, SQLITE3_INTEGER);
      $stmt->bindValue(':amPF', $amPF, SQLITE3_TEXT);
      $stmt->bindValue(':amOPF', $amOPF, SQLITE3_TEXT);
      $stmt->bindValue(':pmPF', $pmPF, SQLITE3_TEXT);
      $stmt->bindValue(':pmOPF', $pmOPF, SQLITE3_TEXT);
      $stmt->bindValue(':loopdes', $loopdes, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // receives an array of Bus Service objects
    protected function _insertMultiple(array $beArr){
      $db = new DB();

      foreach($beArr as $be){
        $serviceno = $be->getServiceNo();
        $op = $be->getOperator();
        $dir = $be->getDirection();
        $cat = $be->getCategory();
        $oC = $be->getOriginCode();
        $dC = $be->getDestinationCode();
        $amPF = $be->getAM_Peak_Freq();
        $amOPF = $be->getAM_Offpeak_Freq();
        $pmPF = $be->getPM_Peak_Freq();
        $pmOPF = $be->getPM_Offpeak_Freq();
        $loopdes = $be->getLoopDesc();
        $sql = "INSERT INTO busservices(serviceno, operator, direction, category, origincode, destinationcode, ampeakfreq, amoffpeakfreq, pmpeakfreq, pmoffpeakfreq, loopdescription)
                VALUES(:serviceno, :op, :dir, :cat, :oC, :dC, :amPF, :amOPF, :pmPF, :pmOPF, :loopdes);";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':serviceno', $serviceno, SQLITE3_TEXT);
        $stmt->bindValue(':op', $op, SQLITE3_TEXT);
        $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
        $stmt->bindValue(':cat', $cat, SQLITE3_TEXT);
        $stmt->bindValue(':oC', $oC, SQLITE3_INTEGER);
        $stmt->bindValue(':dC', $dC, SQLITE3_INTEGER);
        $stmt->bindValue(':amPF', $amPF, SQLITE3_TEXT);
        $stmt->bindValue(':amOPF', $amOPF, SQLITE3_TEXT);
        $stmt->bindValue(':pmPF', $pmPF, SQLITE3_TEXT);
        $stmt->bindValue(':pmOPF', $pmOPF, SQLITE3_TEXT);
        $stmt->bindValue(':loopdes', $loopdes, SQLITE3_TEXT);
        $stmt->execute();
      }

      $db->close();
      return;
    }

    // updates the row in the database by passing in a Bus Service object with the desired service no and direction
    protected function _update(DomainObjectAbstract $obj){
      $db = new DB();

      $serviceno = $obj->getServiceNo();
      $op = $obj->getOperator();
      $dir = $obj->getDirection();
      $cat = $obj->getCategory();
      $oC = $obj->getOriginCode();
      $dC = $obj->getDestinationCode();
      $amPF = $obj->getAM_Peak_Freq();
      $amOPF = $obj->getAM_Offpeak_Freq();
      $pmPF = $obj->getPM_Peak_Freq();
      $pmOPF = $obj->getPM_Offpeak_Freq();
      $loopdes = $obj->getLoopDesc();
      $sql = "UPDATE busservices
              SET operator=:op, category=:cat, origincode=:oC, destinationcode=:dC, ampeakfreq=:amPF, amoffpeakfreq=:amOPF, pmpeakfreq=:pmPF, pmoffpeakfreq=:pmOPF, loopdescription=:loopdes
              WHERE serviceno = :serviceno AND direction = :dir;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':serviceno', $serviceno, SQLITE3_TEXT);
      $stmt->bindValue(':op', $op, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->bindValue(':cat', $cat, SQLITE3_TEXT);
      $stmt->bindValue(':oC', $oC, SQLITE3_INTEGER);
      $stmt->bindValue(':dC', $dC, SQLITE3_INTEGER);
      $stmt->bindValue(':amPF', $amPF, SQLITE3_TEXT);
      $stmt->bindValue(':amOPF', $amOPF, SQLITE3_TEXT);
      $stmt->bindValue(':pmPF', $pmPF, SQLITE3_TEXT);
      $stmt->bindValue(':pmOPF', $pmOPF, SQLITE3_TEXT);
      $stmt->bindValue(':loopdes', $loopdes, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // remove the row in the database
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

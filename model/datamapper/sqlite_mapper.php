<?php

include 'database_mapper.php';


class DB extends SQLite3{
  function __construct() {
    $this->open('BusFeed.db');
  }
}

class SQLite_Mapper implements iDatabase_Mapper
{
    public function __construct(){

    }

    // CREATE TABLE ==========================================================================================

    //Creates a Bus Stop Table if it doesn't exist
    public function createBusStopTable(){
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

    //Creates a Bus Service Table if it doesn't exist
    public function createBusServiceTable(){
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

    //Creates a Bus Route Table if it doesn't exist
    public function createBusRouteTable(){
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


    // CRUD Bus Stop ==========================================================================================

    // add a new bus stop
    // array must be in the form of (busstopcode, roadname, longitude, latitude, description) with type (int, String, float, float, String)
    public function addBusstop(array $bsArray){
      $db = new DB();

      $bscode = $bsArray[0];
      $roadname = $bsArray[1];
      $long = $bsArray[2];
      $lat = $bsArray[3];
      $des = $bsArray[4];
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

    // add multiple bus stops using a 2 dimensional array of bus stops
    public function addMultBusstop(array $bsMultArray){
      $db = new DB();

      foreach($bsMultArray as $bs){
        $bscode = $bs[0];
        $roadname = $bs[1];
        $long = $bs[2];
        $lat = $bs[3];
        $des = $bs[4];
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

    // returns an array of (busstopcode(int), roadname(String), longitude(float), latitude(float), description(String))
    public function getBusstop(int $busStopCode){
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
      return $row;
    }

    // gets all the bus stops as a 2D array of bus stops with their details
    // note: use the following code to retrieve individual results:
    // foreach($testresult as $result) {
    //    echo $result['busstopcode'], '<br>';}
    public function getAllBusstop(){
      $db = new DB();

      $sql = "SELECT busstopcode, roadname, longitude, latitude, description
              FROM busstops;";
      $stmt = $db->prepare($sql);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }

      $db->close();
      return $rowArray;
    }

    //removes a row with the given busstopcode
    public function removeBusstop(int $busStopCode){
      $db = new DB();
      $bscode = $busStopCode;

      $sql = "DELETE FROM busstops
              WHERE busstopcode = :bscode;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':bscode', $bscode, SQLITE3_TEXT);
      $stmt->execute();

      $db->close();
      return;
    }

    // CRUD BUSSERVICE ==========================================================================================

    // add a new bus service
    // array must be in the form of (serviceno(String), op(String), dir(int), cat(String), oC(int), dC(int), amPF(String), amOPF(String), pmPF(String), pmOPF(String), loopdes(String))
    public function addBusservice(array $bseArray){
      $db = new DB();

      $serviceno = $bseArray[0];
      $op = $bseArray[1];
      $dir = $bseArray[2];
      $cat = $bseArray[3];
      $oC = $bseArray[4];
      $dC = $bseArray[5];
      $amPF = $bseArray[6];
      $amOPF = $bseArray[7];
      $pmPF = $bseArray[8];
      $pmOPF = $bseArray[9];
      $loopdes = $bseArray[10];
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

    // add multiple bus services using a 2 dimensional array of busservices
    public function addMultBusservice(array $bseMultArray){
      $db = new DB();
      foreach($bseMultArray as $bs){
        $serviceno = $bs[0];
        $op = $bs[1];
        $dir = $bs[2];
        $cat = $bs[3];
        $oC = $bs[4];
        $dC = $bs[5];
        $amPF = $bs[6];
        $amOPF = $bs[7];
        $pmPF = $bs[8];
        $pmOPF = $bs[9];
        $loopdes = $bs[10];
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

    // returns an array of (serviceno(String), operator(String), direction(int), category(String), origincode(int), destinationcode(int), ampeakfreq(String), amoffpeakfreq(String),
    //          pmpeakfreq(String), pmoffpeakfreq(String), loopdescription(String))
    public function getBusservice(string $serviceNo, int $direction){
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
      return $row;
    }

    // gets all the bus stops as a 2D array of bus stops with their details
    // note: use the following code to retrieve individual results:
    // foreach($testresult as $result) {
    //    echo $result['busstopcode'], '<br>';}
    public function getAllBusservice(){
      $db = new DB();

      $sql = "SELECT serviceno, operator, direction, category, origincode, destinationcode, ampeakfreq, amoffpeakfreq, pmpeakfreq, pmoffpeakfreq, loopdescription
              FROM busservices;";
      $stmt = $db->prepare($sql);
      $res = $stmt->execute();

      $rowArray = array();
      while($row = $res->fetchArray(SQLITE3_ASSOC)){
        array_push($rowArray, $row);
      }

      $db->close();
      return $rowArray;
    }

    //removes a row with the given serviceno and direction
    public function removeBusservice(string $serviceNo, int $direction){
      $db = new DB();
      $sNo = $serviceNo;
      $dir = $direction;

      $sql = "DELETE FROM busservices
              WHERE serviceno = :sNo AND direction = :dir;";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':sNo', $sNo, SQLITE3_TEXT);
      $stmt->bindValue(':dir', $dir, SQLITE3_INTEGER);
      $stmt->execute();

      $db->close();
      return;
    }

    // CRUD BUSROUTE =========================================================================================

    // add a bus route
    // array must be in the form of (serviceno(String), operator(String), direction(int), stopsequence(int), busstopcode(int), distance(float), wdfirstbus(String), wdlastbus(String),
    //             satfirstbus(String), satlastbus(String), sunfirstbus(String), sunlastbus(String))
    public function addBusRouteStop(array $brArray){
      $db = new DB();

      $serviceno = $brArray[0];
      $op = $brArray[1];
      $dir = $brArray[2];
      $stopseq = $brArray[3];
      $bscode = $brArray[4];
      $dist = $brArray[5];
      $wdFB = $brArray[6];
      $wdLB = $brArray[7];
      $satFB = $brArray[8];
      $satLB = $brArray[9];
      $sunFB = $brArray[10];
      $sunLB = $brArray[11];
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

    // add multiple bus routes using a 2 dimensional array of bus routes
    public function addMultBusRoute(array $brMultArray){
      $db = new DB();
      foreach($brMultArray as $br){
        $serviceno = $br[0];
        $op = $br[1];
        $dir = $br[2];
        $stopseq = $br[3];
        $bscode = $br[4];
        $dist = $br[5];
        $wdFB = $br[6];
        $wdLB = $br[7];
        $satFB = $br[8];
        $satLB = $br[9];
        $sunFB = $br[10];
        $sunLB = $br[11];
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


    // returns a 2D array of busRouteStops with the respective format (serviceno(String), operator(String), direction(int), stopsequence(int), busstopcode(int),
    //             distance(float), wdfirstbus(String), wdlastbus(String), satfirstbus(String), satlastbus(String), sunfirstbus(String), sunlastbus(String))
    //
    // note: use the following code to retrieve individual results:
    // foreach($testresult as $result) {
    //    echo $result['busstopcode'], '<br>';}

    public function getBusRoute(string $serviceNo, int $direction){
      $db = new DB();

      $sNo = $serviceNo;
      $dir = $direction;

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

      $db->close();
      return $rowArray;
    }

    //removes an entire bus route (multiple rows) with the given serviceno and direction
    public function removeBusRoute(string $serviceNo, int $direction){
      $db = new DB();

      $sNo = $serviceNo;
      $dir = $direction;

      $sql = "DELETE FROM busroutes
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

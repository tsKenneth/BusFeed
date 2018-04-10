<?php


require_once('../../../model/datamapper/sqlite/BusstopMapper_sqlite.php');


//Cyclometic Complexity = 4
final class GetByBusStopCodeTest
{
    public static function testBusStopCreatedFromValidBusStopCode($busStopCode){
      echo('Checking testBusStopCreatedFromValidBusStopCode --- '.$busStopCode.' --- ');
      $mapper = new BusstopMapper_sqlite();
      $result = $mapper->getByBusStopCode($busStopCode);
      if ($result instanceof Busstop){
        echo('Test Succeeded'.'<br>');
      }
      else{
        echo('Test Failed --- Not an instance of Bus Stop'.'<br>');
      }

    }

    public static function testInvalidBusStopCodeNotInDatabase($busStopCode){
      echo('Checking testInvalidBusStopCodeNotInDatabase --- '.$busStopCode.' --- ');
      $mapper = new BusstopMapper_sqlite();
      $result = $mapper->getByBusStopCode($busStopCode);
      if ($result == 'Row does not exist in database'){
        echo('Test Succeeded'.'<br>');
      }
      else{
        echo('Test Failed -'.$result.'<br>');
      }
    }

}

GetByBusStopCodeTest::testBusStopCreatedFromValidBusStopCode(1112);
GetByBusStopCodeTest::testInvalidBusStopCodeNotInDatabase(333333);

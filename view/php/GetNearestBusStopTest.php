<?php


require_once('../../../model/datamapper/sqlite/BusstopMapper_sqlite.php');


//Cyclometic Complexity = 3
final class GetNearestBusStopTest
{
    public static function testNearestBusStopFromValidInfo($cLong,$cLat,$maxdist){
      echo('Checking testNearestBusStopFromValidInfo --- cLong: '.$cLong.' cLat: '.$cLat.' maxdist: '.$maxdist.' --- ');
      $mapper = new BusstopMapper_sqlite();
      $bsArr = $mapper->getNearestBusstop($cLong,$cLat,$maxdist);
      $success = FALSE;
      foreach($bsArr as $bs){
        if ($bs instanceof Busstop){
          $success = TRUE;
        }
      }
      if($success){
        echo('Test Succeeded '.'<br>');
      }
      else{
        echo('Test Failed --- Not an instance of Bus Stop in the array'.'<br>');
      }
    }

    public static function testNearestBusStopNoResultFromInvalidInfo($cLong,$cLat,$maxdist){
      echo('Checking testNearestBusStopNoResultFromInvalidInfo --- cLong: '.$cLong.' cLat: '.$cLat.' maxdist: '.$maxdist.' --- ');
      $mapper = new BusstopMapper_sqlite();
      $result = $mapper->getNearestBusstop($cLong,$cLat,$maxdist);
      if ($result == 'There are no Bus Stops that fits the criteria'){
        echo('Test Succeeded'.'<br>');
      }
      else{
        echo('Test Failed --- '.$result.'<br>');

      }
    }

}

GetNearestBusStopTest::testNearestBusStopFromValidInfo(103.68,1.348,10);
GetNearestBusStopTest::testNearestBusStopNoResultFromInvalidInfo('-abc', 1.348, 10);

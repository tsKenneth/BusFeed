<?php


require_once('datamapper/sqlite/BusstopMapper_sqlite.php');
require_once('datamapper/sqlite/BusserviceMapper_sqlite.php');
require_once('datamapper/sqlite/BusrouteMapper_sqlite.php');
require_once(__DIR__.'/../view/php/apiHandlerLTA.php');

//TEST BUS STOP
$mapper = new BusstopMapper_sqlite();
//$mapper->createTable();
//$mapper->storeFromAPI();
//$result = $mapper->getByBusStopCode(5651);
//echo($result->getRoadName());
//echo('<tr>');

//$resultarr = $mapper->getAllBusstop();
//foreach($resultarr as $res){
  //echo($res->getRoadName().'<br>');
//}

// TEST BUS SERVICE
//$mapper2 = new BusserviceMapper_sqlite();
//$mapper2->createTable();
//$mapper2->storeFromAPI();
//$result2 = $mapper2->getByServiceNoDirection(912,1);
//echo($result2->getLoopDesc());
//echo('<br>');

//$resultarr2 = $mapper2->getAllBusservice();
//foreach($resultarr2 as $res2){
  //echo($res2->getLoopDesc().'<br>');
//}

// TEST BUS ROUTE
$mapper3 = new BusrouteMapper_sqlite();
//$mapper3->createTable();
//$mapper3->storeFromAPI();
//$result3 = $mapper3->getRouteFull('100',1);
$result4 = $mapper3->getRouteStop('238',1,52321);
//foreach($result3 as $res){
  //echo($res->getBusStopCode().'<br>');
//}
//echo($result4->getDistance());


//GET ARRIVAL TIMINGS TEST
$result5 = $result4->getArrivalTimingsAPI();
echo(($result5[0])->getArrivalMinute());
echo('<br>');
echo(($result5[1])->getArrivalMinute());
echo('<br>');
echo(($result5[2])->getArrivalMinute());
echo('<br>');
echo(gettype(($result5[0])->getArrivalMinute()));



//GET NEAREST TEST

//$bsArr = $mapper->getNearestBusstop(103.68,1.348,5);
//foreach($bsArr as $bs){
  //echo($bs->getRoadName());
//}

//$testy = APIBusArrival(52321,'238');
//echo($testy);
?>

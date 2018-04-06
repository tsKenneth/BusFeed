<?php


require_once('datamapper/sqlite/BusstopMapper_sqlite.php');
require_once('datamapper/sqlite/BusserviceMapper_sqlite.php');
require_once('datamapper/sqlite/BusrouteMapper_sqlite.php');

//TEST BUS STOP
//$mapper = new BusstopMapper_sqlite();
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
$result3 = $mapper3->getRouteFull('100',1);
$result4 = $mapper3->getRouteStop('10',2,75009);
foreach($result3 as $res){
  echo($res->getBusStopCode().'<br>');
}
echo($result4->getDistance());

?>

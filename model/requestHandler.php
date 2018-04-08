<?php
require_once("datamapper\sqlite\BusstopMapper_sqlite.php");
require_once("datamapper\sqlite\BusserviceMapper_sqlite.php");
require_once("datamapper\sqlite\BusrouteMapper_sqlite.php");

if(isset($_GET['function'])) {
    if($_GET['function'] == 'getAllBusstopJSON') {
        echo getAllBusstopJSON();
    } elseif($_GET['function'] == 'getAllBusserviceJSON') {
        echo getAllBusserviceJSON();
    } elseif($_GET['function'] == 'getBusstopJSON' && isset($_GET['busstopCode'])) {
        echo getBusstopJSON($_GET['busstopCode']);
    } elseif($_GET['function'] == 'getBusserviceJSON' && isset($_GET['serviceNo']) && isset($_GET['direction'])) {
        echo getBusserviceJSON($_GET['serviceNo'],$_GET['direction']);
    } elseif($_GET['function']=="getNearbyBusstop" && isset($_GET['pos'])) {
        echo getNearbyBusstopJSON($_GET['pos']);
    } elseif($_GET['function']=="getRoutesAtBusstop" && isset($_GET['busstopcode'])) {
        echo getRoutesAtBusstopJSON($_GET['busstopcode']);
    } elseif($_GET['function']=="getBusArrivalTiming"
        && isset($_GET['busstopcode'])
        && isset($_GET['serviceno'])
        && isset($_GET['direction'])) {
        echo getBusArrivalTimingJSON($_GET['busstopcode'],$_GET['serviceno'],$_GET['direction']);
    }
    else {
        echo "";
    }
}

function getBusArrivalTimingJSON(string $busstopcode, string $serviceno, string $direction) {
    $mapper = new BusrouteMapper_sqlite();
    $results = $mapper->getRouteStop($serviceno, $direction, $busstopcode);
    $resultsArrivalTiming = json_encode($results->getArrivalTimingsAPI());
    echo(strip_tags($resultsArrivalTiming));
}

function getRoutesAtBusstopJSON(string $busstopcode) {
    $mapper = new BusrouteMapper_sqlite();
    $results = json_encode($mapper->getRoutesFromBusStop($busstopcode));
    echo(strip_tags($results));
}

function getNearbyBusstopJSON(string $pos) {
    $mapper = new BusstopMapper_sqlite();
    $pos = json_decode($pos);
    $results = json_encode($mapper->getNearestBusstop($pos->lng,$pos->lat,0.5));
    echo(strip_tags($results));
}

function getBusstopJSON(string $busstopCode) {
    $mapper = new BusstopMapper_sqlite();
    $results = json_encode($mapper->getBusstop($busstopCode));
    echo(strip_tags($results));
}

function getAllBusstopJSON() {
    $mapper = new BusstopMapper_sqlite();
    $results = json_encode($mapper->getAllBusstop());
    echo(strip_tags($results));
}

function getBusserviceJSON(string $serviceNo, string $direction) {
    $mapper = new BusserviceMapper_sqlite();
    $results = json_encode($mapper->getByServiceNoDirection($serviceNo,$direction));
    echo(strip_tags($results));
}

function getAllBusserviceJSON() {
    $mapper = new BusserviceMapper_sqlite();
    $results = json_encode($mapper->getAllBusservice());
    echo(strip_tags($results));
}
 ?>

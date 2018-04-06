<?php
require_once("datamapper\sqlite\BusstopMapper_sqlite.php");
require_once("datamapper\sqlite\BusserviceMapper_sqlite.php");

if(isset($_GET['function'])) {
    if($_GET['function'] == 'getAllBusstopJSON') {
        echo getAllBusstopJSON();
    } elseif($_GET['function'] == 'getAllBusserviceJSON') {
        echo getAllBusserviceJSON();
    } elseif($_GET['function'] == 'getBusstopJSON' && isset($_GET['busstopCode'])) {
        echo getBusstopJSON($_GET['busstopCode']);
    }elseif($_GET['function'] == 'getBusserviceJSON' && isset($_GET['serviceNo']) && isset($_GET['direction'])) {
        echo getBusserviceJSON($_GET['serviceNo'],$_GET['direction']);
    }else {
        echo "";
    }
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

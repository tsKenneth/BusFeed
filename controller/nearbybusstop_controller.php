<?php

require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");
require_once("..\model\Busarrival_Model.php");

class NearbyBusstop_Controller
{
    public static function retrieveBusArrivalTiming(string $busstopcode, string $serviceno, string $direction) {
        $results = Busroute::retrieveBusroute($serviceno, $direction, $busstopcode);
        $resultsArrivalTiming = json_encode($results->getArrivalTimingsAPI());
        return strip_tags($resultsArrivalTiming);
    }

    public static function retrieveRoutesAtBusstop(string $busstopcode) {
        $results = json_encode(Busroute::retrieveRouteFromBusstop($busstopcode));
        return strip_tags($results);
    }

    public static function retrieveNearbyBusstop(string $pos) {
        $pos = json_decode($pos);
        $results = json_encode(Busstop::retrieveNearbyBusstops($pos->lng,$pos->lat,0.5));
        return strip_tags($results);
    }
}

if(isset($_GET['function'])) {
    if($_GET['function']=="retrieveNearbyBusstop" && isset($_GET['pos'])) {
        echo NearbyBusstop_Controller::retrieveNearbyBusstop($_GET['pos']);
    } elseif($_GET['function']=="retrieveRoutesAtBusstop" && isset($_GET['busstopcode'])) {
        echo NearbyBusstop_Controller::retrieveRoutesAtBusstop($_GET['busstopcode']);
    } elseif($_GET['function']=="retrieveBusArrivalTiming"
        && isset($_GET['busstopcode'])
        && isset($_GET['serviceno'])
        && isset($_GET['direction'])) {
        echo NearbyBusstop_Controller::retrieveBusArrivalTiming($_GET['busstopcode'],$_GET['serviceno'],$_GET['direction']);
    }
    else {
        echo "";
    }
}


?>

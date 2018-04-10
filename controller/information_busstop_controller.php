<?php
include 'information_controller.php';
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");

class Information_Busstop_Controller implements iInformation_controller
{
    public static function retrieveInfo(string $specifier) {
        $results = json_encode(Busstop::retrieveBusstop($specifier));
        return (strip_tags($results));
    }

    public static function retrieveAllInfo() {
        $results = json_encode(Busstop::retrieveAllBusstops());
        return (strip_tags($results));
    }

    public static function retrieveBusRouteFromBusStop(string $busstop) {
        $results = json_encode(Busroute::retrieveRouteFromBusstop($busstop));
        return (strip_tags($results));
    }

    public static function retrieveBusArrivalTiming(string $busstopcode, string $serviceno, string $direction) {
        $results = Busroute::retrieveBusroute($serviceno, $direction, $busstopcode);
        $resultsArrivalTiming = json_encode($results->getArrivalTimingsAPI());
        return strip_tags($resultsArrivalTiming);
    }
}

if(isset($_GET['function'])) {
    if($_GET['function'] == 'retrieveAllInfo') {
        echo Information_Busstop_Controller::retrieveAllInfo();
    } elseif($_GET['function'] == 'retrieveInfo' && isset($_GET['busstopcode'])) {
        echo Information_Busstop_Controller::retrieveInfo($_GET['busstopcode']);
    } elseif($_GET['function'] == 'retrieveBusRouteFromBusStop' && isset($_GET['busstopcode'])) {
        echo Information_Busstop_Controller::retrieveBusRouteFromBusStop($_GET['busstopcode']);
    }elseif($_GET['function']=="retrieveBusArrivalTiming"
        && isset($_GET['busstopcode'])
        && isset($_GET['serviceno'])
        && isset($_GET['direction'])) {
        echo Information_Busstop_Controller::retrieveBusArrivalTiming($_GET['busstopcode'],$_GET['serviceno'],$_GET['direction']);
    }  else {
        echo "";
    }
}



?>

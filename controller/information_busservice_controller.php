<?php
include 'information_controller.php';
require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");
require_once("..\model\Busarrival_Model.php");

class Information_Busservice_Controller implements iInformation_controller
{
    public static function retrieveInfo(string $specifier) {
        $specifiers = json_decode($specifier);
        $results = json_encode(Busservice::retrieveBusService($specifiers->serviceNo,$specifiers->direction));
        return (strip_tags($results));
    }

    public static function retrieveAllInfo() {
        $results = json_encode(Busservice::retrieveAllBusServices());
        return (strip_tags($results));
    }

    public static function retrieveBusRouteFromService(string $serviceNo, string $direction) {
        $results = json_encode(Busroute::retrieveAllBusRoute($serviceNo,$direction));
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
        echo Information_Busservice_Controller::retrieveAllInfo();
    } elseif($_GET['function'] == 'retrieveInfo' && isset($_GET['serviceNo']) && isset($_GET['direction'])) {
        $tempJSON = new stdClass();
        $tempJSON->serviceNo = $_GET['serviceNo'];
        $tempJSON->direction = $_GET['direction'];
        $tempJSON = json_encode($tempJSON);
        echo Information_Busservice_Controller::retrieveInfo($tempJSON);
    } elseif($_GET['function'] == 'retrieveBusRouteFromService' && isset($_GET['serviceNo']) && isset($_GET['direction'])) {
        echo Information_Busservice_Controller::retrieveBusRouteFromService($_GET['serviceNo'],$_GET['direction']);
    } elseif($_GET['function']=="retrieveBusArrivalTiming"
        && isset($_GET['busstopcode'])
        && isset($_GET['serviceno'])
        && isset($_GET['direction'])) {
        echo Information_Busservice_Controller::retrieveBusArrivalTiming($_GET['busstopcode'],$_GET['serviceno'],$_GET['direction']);
    } else {
        echo "";
    }
}



?>

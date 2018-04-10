<?php
require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");

class Bookmarks_Controller
{
    public static function retrieveBusServiceBookmarkInfo(string $userData) {
        $userDataArr = json_decode($userData);
        $resultsArr = Busservice::retrieveAllBusServices();
        $filteredArr = array();
        foreach ($resultsArr as $key => $value) {
            foreach($userDataArr as $index => $busService) {
                if($value->getServiceNo() == $busService->serviceNo
                    && $value->getDirection() == $busService->direction) {
                    array_push($filteredArr,$value);
                }
            }
        }
        $results = json_encode($filteredArr);
        return (strip_tags($results));
    }
    public static function retrieveBusstopBookmarkInfo(string $userData) {
        $userDataArr = json_decode($userData);
        $resultsArr = Busstop::retrieveAllBusstops();
        $filteredArr = array();
        foreach ($resultsArr as $key => $value) {
            foreach($userDataArr as $index => $busstop) {
                if($value->getBusstopCode() == $busstop) {
                    array_push($filteredArr,$value);
                }
            }
        }
        $results = json_encode($filteredArr);
        return (strip_tags($results));
    }

    public static function retrieveBusArrivalTiming(string $busstopcode, string $serviceno, string $direction) {
        $results = Busroute::retrieveBusroute($serviceno, $direction, $busstopcode);
        $resultsArrivalTiming = json_encode($results->getArrivalTimingsAPI());
        return strip_tags($resultsArrivalTiming);
    }
}

if(isset($_GET['function'])) {
    if($_GET['function'] == 'retrieveBusServiceBookmarkInfo' && isset($_GET['usercookies'])) {
        echo Bookmarks_Controller::retrieveBusServiceBookmarkInfo($_GET['usercookies']);
    }elseif($_GET['function'] == 'retrieveBusstopBookmarkInfo' && isset($_GET['usercookies'])) {
        echo Bookmarks_Controller::retrieveBusstopBookmarkInfo($_GET['usercookies']);
    }elseif($_GET['function']=="retrieveBusArrivalTiming"
        && isset($_GET['busstopcode'])
        && isset($_GET['serviceno'])
        && isset($_GET['direction'])) {
        echo Information_Busservice_Controller::retrieveBusArrivalTiming($_GET['busstopcode'],$_GET['serviceno'],$_GET['direction']);
    } else {
        echo "";
    }
}

?>

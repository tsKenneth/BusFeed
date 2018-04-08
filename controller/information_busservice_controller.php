<?php
include 'information_controller.php';
require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");
require_once("..\model\Busarrival_Model.php");

if(isset($_GET['function'])) {
    if($_GET['function'] == 'getAllBusserviceJSON') {
        echo getAllBusserviceJSON();
    } elseif($_GET['function'] == 'getBusserviceJSON' && isset($_GET['serviceNo']) && isset($_GET['direction'])) {
        echo getBusserviceJSON({serviceNo: $_GET['serviceNo'], direction: $_GET['direction']);
    }
    else {
        echo "";
    }
}

class Information_Busservice_Controller implements iInformation_controller
{
    public static function retrieveInfo(string $specifier) {
        $specifiers = json_decode($specifier);
        $results = json_encode(Busservice::retrieveBusService($specifiers.serviceNo,$specifiers.direction));
        return (strip_tags($results));
    }

    public static function retrieveAllInfo() {
        $results = json_encode(Busservice::retrieveAllBusServices());
        return (strip_tags($results));
    }
}

?>

<?php
include 'information_controller.php';
require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");
require_once("..\model\Busarrival_Model.php");

if(isset($_GET['function'])) {
    if($_GET['function'] == 'retrieveAllInfo') {
        echo Information_Busstop_Controller::retrieveAllInfo();
    } elseif($_GET['function'] == 'getBusstopJSON' && isset($_GET['busstopCode'])) {
        echo Information_Busstop_Controller::retrieveInfo($_GET['busstopCode']);
    }
    else {
        echo "";
    }
}

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
}

?>

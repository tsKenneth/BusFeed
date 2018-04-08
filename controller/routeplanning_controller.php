<?php
require_once("..\model\Busservice_Model.php");
require_once("..\model\Busroute_Model.php");
require_once("..\model\Busstop_Model.php");
require_once("..\model\Busarrival_Model.php");


class Information_Busstop_Controller
{
    // Returns the array of information that details the busroute and takes in the busservice number
    public function retrieveRoute(string $serviceNo);
}

?>

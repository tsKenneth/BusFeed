<?php
include 'information_controller.php';

class Information_Busstop_Controller implements iInformation_controller
{
    // Returns an array of busstop information
    public function filter(string filterText);
}

?>
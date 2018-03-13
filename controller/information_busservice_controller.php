<?php
include 'information_controller.php';

class Information_Busservice_Controller implements iInformation_controller
{
    // Returns an array of bus service information
    public function filter(string filterText);
}

?>
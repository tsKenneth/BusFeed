<?php

interface iDatabase_Mapper
{
    public function addBusstop($busStopCode, &$busstopInformationArray );
    public function getBusstop($busStopCode);
    public function removeBusstop($busStopCode);
    
    public function addBusservice($serviceNo, &$busserviceInformationArray );
    public function getBusservice($serviceNo);
    public function removeBusservice($serviceNo);
}

?>
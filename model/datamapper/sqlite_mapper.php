<?php

include 'database_mapper.php';

class SQLite_Mapper implements iDatabase_Mapper
{
    public function addBusstop(int $busStopCode, &$busstopInformationArray );
    public function getBusstop(int $busStopCode);
    public function removeBusstop(int $busStopCode);
    
    public function addBusservice(string $serviceNo, &$busserviceInformationArray );
    public function getBusservice(string $serviceNo);
    public function removeBusservice(string $serviceNo);
}

?>
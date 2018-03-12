<?php

include 'database_mapper.php';

class SQLite_Mapper implements iDatabase_Mapper
{
    public function addBusstop(int $busStopCode, array $busstopInformationArray );
    public function getBusstop(int $busStopCode);
    public function removeBusstop(int $busStopCode);
    
    public function addBusservice(string $serviceNo, array $busserviceInformationArray );
    public function getBusservice(string $serviceNo);
    public function removeBusservice(string $serviceNo);
}

?>
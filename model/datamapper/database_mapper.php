<?php

interface iDatabase_Mapper
{
    public function createBusStopTable();
    public function createBusServiceTable();
    public function createBusRouteTable();

    public function addBusstop(array $bsArray);
    public function addMultBusstop(array $bsMultArray);
    public function getBusstop(int $busStopCode);
    public function getAllBusstop();
    public function removeBusstop(int $busStopCode);

    public function addBusservice(array $bseArray);
    public function addMultBusservice(array $bseMultArray);
    public function getBusservice(string $serviceNo, int $direction);
    public function getAllBusservice();
    public function removeBusservice(string $serviceNo, int $direction);


    public function addBusRouteStop(array $brArray);
    public function addMultBusRoute(array $brMultArray);
    public function getBusRoute(string $serviceNo, int $direction);
    public function removeBusRoute(string $serviceNo, int $direction);
}

?>

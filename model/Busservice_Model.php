<?php
require_once('DomainObjectAbstract.php');
require_once('datamapper/sqlite/BusserviceMapper_sqlite.php');

class Busservice extends DomainObjectAbstract{

    // Properties
    private $serviceNo;
    private $operator;
    private $direction;
    private $category;
    private $originCode;
    private $destinationCode;
    private $AM_Peak_Freq;
    private $AM_Offpeak_Freq;
    private $PM_Peak_Freq;
    private $PM_Offpeak_Freq;
    private $loopDesc;

    private $originName;
    private $destinationName;

    public static function retrieveBusService($serviceNo,$direction) {
        $mapper = new BusserviceMapper_sqlite();
        return $mapper->getByServiceNoDirection($serviceNo,$direction);
    }

    public static function retrieveAllBusServices() {
        $mapper = new BusserviceMapper_sqlite();
        return $mapper->getAllBusservice();
    }

    public static function setBusServiceNames(){
        $mapper = new BusserviceMapper_sqlite();
        $mapper->setBusServiceNames();
        return;
    }

    // Methods
    public function JsonSerialize(){
      return [
            'serviceNo' => $this->getServiceNo(),
            'operator' => $this->getOperator(),
            'direction' => $this->getDirection(),
            'category' => $this->getCategory(),
            'originCode' => $this->getOriginCode(),
            'destinationCode' => $this->getDestinationCode(),
            'AM_Peak_Freq' => $this->getAM_Peak_Freq(),
            'AM_Offpeak_Freq' => $this->getAM_Offpeak_Freq(),
            'PM_Peak_Freq' => $this->getPM_Peak_Freq(),
            'PM_Offpeak_Freq' => $this->getPM_Offpeak_Freq(),
            'loopDesc' => $this->getLoopDesc(),
            'originName' => $this->getOriginName(),
            'destinationName' => $this->getDestinationName()
        ];
    }
    // Mutators

    public function setServiceNo($info){
      $this->serviceNo = $info;
    }

    public function setOperator($info){
      $this->operator = $info;
    }

    public function setDirection($info){
      $this->direction = $info;
    }

    public function setCategory($info){
      $this->category = $info;
    }

    public function setOriginCode($info){
      $this->originCode = $info;
    }

    public function setDestinationCode($info){
      $this->destinationCode = $info;
    }

    public function setAMP($info){
      $this->AM_Peak_Freq = $info;
    }

    public function setAMO($info){
      $this->AM_Offpeak_Freq = $info;
    }

    public function setPMP($info){
      $this->PM_Peak_Freq = $info;
    }

    public function setPMO($info){
      $this->PM_Offpeak_Freq = $info;
    }

    public function setLoopDescription($info){
      $this->loopDesc = $info;
    }

    public function setOriginName($info){
      $this->originName = $info;
    }

    public function setDestinationName($info){
      $this->destinationName = $info;
    }


    // Accessors

    public function getServiceNo() {
      return $this->serviceNo;
    }

    public function getOperator() {
      return $this->operator;
    }

    public function getDirection() {
      return $this->direction;
    }

    public function getCategory() {
      return $this->category;
    }

    public function getOriginCode() {
      return $this->originCode;
    }

    public function getDestinationCode() {
      return $this->destinationCode;
    }

    public function getAM_Peak_Freq() {
      return $this->AM_Peak_Freq;
    }

    public function getAM_Offpeak_Freq() {
      return $this->AM_Offpeak_Freq;
    }

    public function getPM_Peak_Freq() {
      return $this->PM_Peak_Freq;
    }

    public function getPM_Offpeak_Freq() {
      return $this->PM_Offpeak_Freq;
    }

    public function getLoopDesc() {
      return $this->loopDesc;
    }

    public function getOriginName() {
      return $this->originName;
    }

    public function getDestinationName() {
      return $this->destinationName;
    }
}
?>

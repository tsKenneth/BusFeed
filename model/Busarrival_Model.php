<?php

require_once('DomainObjectAbstract.php');


/**
 * Class that stores the information of the next bus arrival timing
 */

class Busarrival extends DomainObjectAbstract{


  //Properties
  private $originCode;
  private $destinationCode;
  private $estimatedArrival;
  private $latitude;
  private $longitude;
  private $visitNumber;
  // can either be SEA (Seat Available), SDA (Standing Available), LSD (Limited Standing)
  private $load;
  // can either be WAD (Wheelchair Accessible) , null
  private $feature;
  // can either be SD (Single Deck), DD (Double Deck), BD (Bendy)
  private $type;

  //Methods

  // gets the arrival timing in terms of minute
  public function getArrivalMinute(){
    $currTime = time();
    $arrTime = strtotime($this->getEstimatedArrival());
    $minuteDiff = round(($arrTime-$currTime)/60);
    return $minuteDiff;
  }

  //Mutators
  public function setOriginCode($info){
    $this->originCode = $info;
  }
  public function setDestinationCode($info){
    $this->destinationCode = $info;
  }
  public function setEstimatedArrival($info){
    $this->estimatedArrival = $info;
  }
  public function setLatitude($info){
    $this->latitude = $info;
  }
  public function setLongitude($info){
    $this->longitude = $info;
  }
  public function setVisitNumber($info){
    $this->visitNumber = $info;
  }
  public function setLoad($info){
    $this->load = $info;
  }
  public function setFeature($info){
    $this->feature = $info;
  }
  public function setType($info){
    $this->type = $info;
  }
  //Accessors

  public function getOriginCode(){
    return $this->originCode;
  }
  public function getDestinationCode(){
    return $this->destinationCode;
  }
  public function getEstimatedArrival(){
    return $this->estimatedArrival;
  }
  public function getLatitude(){
    return $this->latitude;
  }
  public function getLongitude(){
    return $this->longitude;
  }
  public function getVisitNumber(){
    return $this->visitNumber;
  }
  public function getLoad(){
    return $this->load;
  }
  public function getFeature(){
    return $this->feature;
  }
  public function getType(){
    return $this->type;
  }






}




?>

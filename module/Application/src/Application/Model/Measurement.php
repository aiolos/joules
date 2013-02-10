<?php
namespace Application\Model;

class Measurement
{
    protected $measurement;
    protected $timestamp;
    protected $watt;

    public function getMeasurement() {
        return $this->measurement;
    }

    public function setMeasurement($id) {
        $this->measurement = $id;
        
        return $this;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        
        return $this;
    }

    public function getWatt() {
        return $this->watt;
    }

    public function setWatt($watt) {
        $this->watt = $watt;
        
        return $this;
    }
    
    public function exchangeArray($data)
    {
        $this->measurement     = (isset($data['meting'])) ? $data['meting'] : null;
        $this->timestamp = (isset($data['timestamp'])) ? $data['timestamp'] : null;
        $this->watt  = (isset($data['watt'])) ? $data['watt'] : null;
    }

}
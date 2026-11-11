<?php
require_once 'rmCollection.php';

class ClassAttribute implements JsonSerializable {

    private $description;
    private $frequency = null;
    private $total = null;

    private $rm_collection;
    public function getRmCollection() {
        return $this->rm_collection;
    }

    public function setRmCollection($rm_collection) {
        $this->rm_collection = $rm_collection;
    }

    public function isCollectionEmpty() {
        if (empty($this->getRmCollection())) {
            return true;
        }

        return count($this->getRmCollection()->getAll()) == 0;
     }
    
    public function __construct($description) {
        $this->$description = $description;
    }

	// function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getDescription() {
        return $this->description;
    }
    public function getFrequency() {
        return $this->frequency;
    }

    public function setFrequency($frequency) {
        $this->frequency = $frequency;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }
}
?>
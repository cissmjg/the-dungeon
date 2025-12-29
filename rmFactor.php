<?php
class RmFactor {

    private $rmDescription;
    public function getRMDescription() {
        return $this->rmDescription;
    }
    
    private $rmData = 0;
    public function getRMData() {
        return $this->rmData;
    }

    function __construct($rm_description, $rm_data) {
        $this->rmDescription = $rm_description;
        $this->rmData = $rm_data;
    }
}
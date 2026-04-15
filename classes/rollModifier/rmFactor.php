<?php
require_once 'rmCategory.php';

class RmFactor {

    private $rmDescription;
    public function getRMDescription() {
        return $this->rmDescription;
    }
    
    private $rmData = 0;
    public function getRMData() {
        return $this->rmData;
    }

    private $rmCategory;
    public function getRmCategory() {
        return $this->rmCategory;
    }

    public function setRmCategory($roll_modifier_category) {
        $this->rmCategory = $roll_modifier_category;
    }

    function __construct($rm_description, $rm_data) {
        $this->rmDescription = $rm_description;
        $this->rmData = $rm_data;
        $this->rmCategory = ROLL_MODIFIER_BONUS;
    }
}
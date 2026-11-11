<?php

require_once 'rmCollection.php';
require_once 'rmCollectionCalculator.php';
require_once 'thiefFunctionLookup.php';

abstract class ThiefAbilityBaseRmCollectionCalculator extends RmCollectionCalculator {
    protected $rm_thief_collection;
    public function getRmCollection() {
        return $this->rm_thief_collection;
    }

    protected $rm_thief_lookup;
    public function getThiefLookupFunction() {
        return $this->rm_thief_lookup;
    }

    public function __construct() {
        $this->rm_thief_collection = new RmCollection();
        $this->rm_thief_lookup = new ThiefFunctionLookup();
    }
}
?>
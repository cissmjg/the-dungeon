<?php
require_once 'rmFactor.php';

class RmAggregator {

    private $rmFactorList = [];

    public function add(\RmFactor $rmFactor) {
        $rmFactorList[] = rmFactor;
    }

    public function aggregate() {
        $rmFactorResult = 0;
        foreach($rmFactorList AS $rmFactor) {
            $rmFactorResult += $rmFactor->getRMData();
        }

        return $rmFactorResult;
    }
}
?>
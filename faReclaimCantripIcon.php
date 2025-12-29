<?php
require_once 'faActionIcon.php';
use faAction;

class FaReclaimCantripIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-minimize";
    }
    
    function buildStyles() {
        parent::addStyle("color: DodgerBlue;");
        return parent::buildStyles();
    }
}
?>
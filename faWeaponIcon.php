<?php
require_once 'faActionIcon.php';
use faAction;

class FaWeaponIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-shield";
    }
    
    function buildStyles() {
        parent::addStyle("color: Black;");
        return parent::buildStyles();
    }
}
?>
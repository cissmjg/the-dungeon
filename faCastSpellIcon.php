<?php
require_once 'faActionIcon.php';
use faAction;

class FaCastSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-wand-sparkles";
    }
    
    function buildStyles() {
        parent::addStyle("color: DodgerBlue;");
        return parent::buildStyles();
    }
}
?>
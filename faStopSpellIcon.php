<?php
require_once 'faActionIcon.php';
use faAction;

class FaStopSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-regular fa-hourglass-half";
    }

    function buildStyles() {
        parent::addStyle("color: Red;");
        return parent::buildStyles();
    }
}
?>
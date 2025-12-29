<?php
require_once 'faActionIcon.php';
use faAction;

class FaHealSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-heart";
    }

    function buildStyles() {
        parent::addStyle("color: Maroon;");
        return parent::buildStyles();
    }
}
?>
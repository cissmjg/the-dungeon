<?php
require_once 'faActionIcon.php';
use faAction;

class FaPraySpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-hands-praying";
    }

    function buildStyles() {
        parent::addStyle("color: ForestGreen;");
        return parent::buildStyles();
    }
}
?>
<?php
require_once 'faActionIcon.php';
use faAction;

class FaRunSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-person-running";
    }

    function buildStyles() {
        parent::addStyle("color: Red;");
        return parent::buildStyles();
    }
}
?>
<?php
require_once 'faActionIcon.php';
use faAction;

class FaEditSlotIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-square-minus";
    }

    function buildStyles() {
        parent::addStyle("color: blue;");
        return parent::buildStyles();
    }
}
?>
<?php
require_once 'faActionIcon.php';
use faAction;

class FaSleepIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-bed";
    }

    function buildStyles() {
        parent::addStyle("color: Maroon;");
        return parent::buildStyles();
    }
}
?>
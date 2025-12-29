<?php
require_once 'faActionIcon.php';
use faAction;

class FaEditIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-pen";
    }

    function buildStyles() {
        parent::addStyle("color: black;");
        return parent::buildStyles();
    }
}
?>
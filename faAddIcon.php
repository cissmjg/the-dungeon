<?php
require_once 'faActionIcon.php';
use faAction;

class FaAddIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-plus";
    }

    function buildStyles() {
        parent::addStyle("color: dodgerblue;");
        return parent::buildStyles();
    }
}
?>
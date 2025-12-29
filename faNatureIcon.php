<?php
require_once 'faActionIcon.php';
use faAction;

class FaNatureIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-tree";
    }

    function buildStyles() {
        parent::addStyle("color: MediumSpringGreen;");
        return parent::buildStyles();
    }
}
?>
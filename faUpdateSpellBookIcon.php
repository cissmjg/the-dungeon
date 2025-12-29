<?php
require_once 'faActionIcon.php';
use faAction;

class FaUpdateSpellBookIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-feather-pointed";
    }

    function buildStyles() {
        parent::addStyle("color: OrangeRed;");
        return parent::buildStyles();
    }
}
?>
<?php
require_once 'faActionIcon.php';
use faAction;

class FaMemorizeSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-book-open-reader";
    }

    function buildStyles() {
        parent::addStyle("color: Blue;");
        return parent::buildStyles();
    }
}
?>
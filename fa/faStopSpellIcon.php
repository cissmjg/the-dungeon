<?php
require_once 'faActionIcon.php';

class FaStopSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-regular fa-hourglass-half";
    }

    public function buildStyles() {
        parent::addStyle("color: Red;");
        return parent::buildStyles();
    }
}
?>
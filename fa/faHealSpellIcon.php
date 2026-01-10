<?php
require_once 'faActionIcon.php';

class FaHealSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-heart";
    }

    public function buildStyles() {
        parent::addStyle("color: Maroon;");
        return parent::buildStyles();
    }
}
?>
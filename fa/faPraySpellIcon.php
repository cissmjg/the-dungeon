<?php
require_once 'faActionIcon.php';

class FaPraySpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-hands-praying";
    }

    public function buildStyles() {
        parent::addStyle("color: ForestGreen;");
        return parent::buildStyles();
    }
}
?>
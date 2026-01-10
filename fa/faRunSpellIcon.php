<?php
require_once 'faActionIcon.php';

class FaRunSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-person-running";
    }

    public function buildStyles() {
        parent::addStyle("color: Red;");
        return parent::buildStyles();
    }
}
?>
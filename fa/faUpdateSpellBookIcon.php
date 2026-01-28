<?php
require_once 'faActionIcon.php';

class FaUpdateSpellBookIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-feather-pointed";
    }

    public function buildStyles() {
        parent::addStyle("color: OrangeRed;");
        return parent::buildStyles();
    }
}
?>
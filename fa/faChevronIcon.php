<?php
require_once 'faActionIcon.php';

class FaChevronIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-chevron-down rm-chevron";
    }
    public function buildStyles() {
        parent::addStyle("color: Black;");
        return parent::buildStyles();
    }
}
?>

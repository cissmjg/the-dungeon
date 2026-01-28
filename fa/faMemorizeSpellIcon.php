<?php
require_once 'faActionIcon.php';

class FaMemorizeSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-book-open-reader";
    }

    public function buildStyles() {
        parent::addStyle("color: Blue;");
        return parent::buildStyles();
    }
}
?>
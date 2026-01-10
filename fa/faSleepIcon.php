<?php
require_once 'faActionIcon.php';

class FaSleepIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-bed";
    }

    public function buildStyles() {
        parent::addStyle("color: Maroon;");
        return parent::buildStyles();
    }
}
?>
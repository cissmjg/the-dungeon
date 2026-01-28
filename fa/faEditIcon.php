<?php
require_once 'faActionIcon.php';

class FaEditIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-pen";
    }

    public function buildStyles() {
        parent::addStyle("color: black;");
        return parent::buildStyles();
    }
}
?>
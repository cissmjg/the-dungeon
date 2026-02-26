<?php
require_once 'faActionIcon.php';

class FaNewIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-square-plus";
    }

    public function buildStyles() {
        parent::addStyle("color: navy;");
        return parent::buildStyles();
    }
}
?>
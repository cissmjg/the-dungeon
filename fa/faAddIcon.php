<?php
require_once 'faActionIcon.php';

class FaAddIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-plus";
    }

    public function buildStyles() {
        parent::addStyle("color: dodgerblue;");
        return parent::buildStyles();
    }
}
?>
<?php
require_once 'faActionIcon.php';

class FaNatureIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-tree";
    }

    public function buildStyles() {
        parent::addStyle("color: MediumSpringGreen;");
        return parent::buildStyles();
    }
}
?>
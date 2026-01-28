<?php
require_once 'faActionIcon.php';

class FaCancelIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-xmark";
    }

    public function buildStyles() {
        parent::addStyle("color: red;");
        return parent::buildStyles();
    }
}
?>
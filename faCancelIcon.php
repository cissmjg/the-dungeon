<?php
require_once 'faActionIcon.php';
use faAction;

class FaCancelIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-xmark";
    }

    function buildStyles() {
        parent::addStyle("color: red;");
        return parent::buildStyles();
    }
}
?>
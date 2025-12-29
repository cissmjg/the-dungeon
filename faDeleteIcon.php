<?php
require_once 'faActionIcon.php';
use faAction;

class FaDeleteIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-trash-can-xmark";
    }

    function buildStyles() {
        parent::addStyle("color: red;");
        return parent::buildStyles();
    }
}
?>

<?php
require_once 'faActionIcon.php';

class FaDeleteIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-trash-can-xmark";
    }

    public function buildStyles() {
        parent::addStyle("color: red;");
        return parent::buildStyles();
    }
}
?>

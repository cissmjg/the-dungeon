<?php
require_once 'faActionIcon.php';
use faAction;

class FaRefreshIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-refresh";
    }

    function buildStyles() {
        parent::addStyle("color: Lime;");
        return parent::buildStyles();
    }
}
?>
<?php
require_once 'faActionIcon.php';

class FaRefreshIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-refresh";
    }

    public function buildStyles() {
        parent::addStyle("color: Lime;");
        return parent::buildStyles();
    }
}
?>
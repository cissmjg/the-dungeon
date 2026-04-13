<?php
class FaSetupIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-gear";
    }

    public function buildStyles() {
        parent::addStyle("color: SeaGreen;");
        return parent::buildStyles();
    }
}
?>
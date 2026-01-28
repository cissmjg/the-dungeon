<?php
require_once 'faActionIcon.php';

class FaCastSpellIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-wand-sparkles";
    }
    
    public function buildStyles() {
        parent::addStyle("color: DodgerBlue;");
        return parent::buildStyles();
    }
}
?>
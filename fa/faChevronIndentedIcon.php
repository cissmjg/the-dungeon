<?php
require_once 'faActionIcon.php';

class FaChevronIndentedIcon extends FaActionIcon {

    protected function getFaClassList() {
        return "fa-solid fa-chevron-down rm-indented-chevron";
    }
    public function buildStyles() {
        parent::addStyle("color: Black;");
        return parent::buildStyles();
    }
}
?>

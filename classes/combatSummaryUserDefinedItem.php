<?php

require_once 'playerCharacterWeaponRendererBase.php';

class CombatSummaryUserDefinedItem implements JsonSerializable {
    private $id;
    private $section_name;
    private $display_order;
    private $renderer_id = '';

    public function init($combat_summary_user_defined_item) {
        $this->id = $combat_summary_user_defined_item['combat_summary_user_defined_id'];
        $this->section_name = $combat_summary_user_defined_item['combat_summary_user_defined_section_name'];
        $this->display_order = $combat_summary_user_defined_item['combat_summary_user_defined_display_order'];
        $this->renderer_id = $combat_summary_user_defined_item['combat_summary_user_defined_renderer_id'];
    }

    public function initFromRenderer($renderer_id, $display_order, $temp_id, $section_name) {
        $this->id = $temp_id;
        $this->display_order = $display_order;
        $this->section_name = $section_name;
        $this->renderer_id = $renderer_id;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    public function getId() {
        return $this->id;
    }

    public function getSectionName() {
        return $this->section_name;
    }

    public function getDisplayOrder() {
        return $this->display_order;
    }

    public function getRendererId() {
        return $this->renderer_id;
    }
}
?>

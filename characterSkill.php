<?php

class CharacterSkill implements JsonSerializable, Stringable {

    private $skill_id;
    private $skill_name;
    private $skill_attribute;
    private $max_count;
    private $can_skill_focus;
    private $roll_name;
    private $attribute_bonus;
    private $skill_notes;
    private $prerequisite_skill_id;

    public function init(\PDO $pdo, $skill_row, &$errors) {
        $this->skill_id = $skill_row['skill_catalog_id'];
        $this->skill_name = $skill_row['skill_catalog_name'];
        $this->skill_attribute = $skill_row['skill_catalog_attribute'];
        $this->max_count = $skill_row['skill_catalog_max_count'];
        $this->can_skill_focus = $skill_row['skill_catalog_skill_focus'];
        $this->roll_name = $skill_row['skill_catalog_roll_name'];
        $this->attribute_bonus = $skill_row['skill_catalog_attribute_bonus'];
        $this->skill_notes = $skill_row['skill_catalog_ability_text'];
        $this->prerequisite_skill_id = $skill_row['skill_prerequisite_skill_id'];
    }

    public function getSkillId() {
        return $this->skill_id;
    }

    public function getSkillName() {
        return $this->skill_name;
    }

    public function getSkillAttribute() {
        return $this->skill_attribute;
    }

    public function getSkillNotes() {
        return $this->skill_notes;
    }

	// function called when encoded with json_encode
    public function jsonSerialize() {
        return get_object_vars($this);
    }
	
	public function __toString() {
		return $this->formatted_name;
	}
}
//  skill_catalog_id	skill_catalog_name	skill_catalog_attribute	skill_catalog_max_count	skill_catalog_skill_focus	skill_catalog_roll_name	skill_catalog_attribute_bonus	skill_catalog_ability_text	skill_prerequisite_skill_id	

?>
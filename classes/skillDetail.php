<?php
    class SkillDetail implements JsonSerializable {
        private  $id;
        private $name;
        private $attribute;
        private $is_skill_focus;
        private $max_count;
        private $required_class_value;
        private $required_race_value;
        private $required_level;
        private $minimum_charisma;
        private $minimum_dexterity;
        private $minimum_intelligence;
        private $roll_name;
        private $ability_text;
        private $attribute_bonus;

        public function init($skill) {
            $this->id = $skill['skill_catalog_id'];
            $this->name = $skill['skill_catalog_name'];
            $this->attribute = $skill['skill_catalog_attribute'];
            $this->is_skill_focus = $skill['skill_catalog_skill_focus'];
            $this->max_count = $skill['skill_catalog_max_count'];
            $this->required_class_value = $skill['skill_catalog_required_class_id'];
            $this->required_race_value = $skill['skill_catalog_required_race_id'];
            $this->required_level = $skill['skill_catalog_required_level'];
            $this->minimum_charisma = $skill['skill_catalog_minimum_charisma'];
            $this->minimum_dexterity = $skill['skill_catalog_minimum_dexterity'];
            $this->minimum_intelligence = $skill['skill_catalog_minimum_intelligence'];
            $this->roll_name = $skill['skill_catalog_roll_name'];
            $this->ability_text = $skill['skill_catalog_ability_text'];
            $this->attribute_bonus = $skill['skill_catalog_attribute_bonus'];
        }

        private $skill_prerequisite_ids = [];
        public function jsonSerialize() {
            return get_object_vars($this);
        }

        public function getSkillId() {
            return $this->id;
        }

        public function getSkillName() {
            return $this->name;
        }

        public function getSkillAttribute() {
            return $this->attribute;
        }

        public function isSkillFocus() {
            return $this->is_skill_focus;
        }

        public function getMaxCount() {
            return $this->max_count;
        }

        public function getRequiredClassId() {
            return $this->required_class_value;
        }

        public function getRequireRaceId() {
            return $this->required_race_value;
        }

        public function getRequiredLevel() {
            return $this->required_level;
        }

        public function getMinCharisma() {
            return $this->minimum_charisma;
        }

        public function getMinDexterity() {
            return $this->minimum_dexterity;
        }

        public function getMinIntelligence() {
            return $this->minimum_intelligence;
        }

        public function getRollName() {
            return $this->roll_name;
        }

        public function getAbilityText() {
            return $this->ability_text;
        }

        public function getAttributeBonus() {
            return $this->attribute_bonus;
        }

        public function getPrerequisiteSKillIds() {
            return $this->skill_prerequisite_ids;
        }

        public function addSkillPrerequisite($skill_id) {
            $this->skill_prerequisite_ids[] = $skill_id;
        }
    }    
?>
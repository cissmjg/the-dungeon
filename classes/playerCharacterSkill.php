<?php

    class PlayerCharacterSkill implements JsonSerializable
    {
        private $id;
        private $skill_catalog_id;
        private $player_character_skill_count;
        private $player_character_skill_name;
        private $is_skill_focus;
        private $weapon_proficiency_id;
        private $weapon2_proficiency_id;

        public function init($skill) {
            $this->id = $skill['player_character_skill_id'];
            $this->skill_catalog_id = $skill['skill_catalog_id'];
            $this->player_character_skill_count = $skill['player_character_skill_count'];
            $this->player_character_skill_name = $skill['skill_name'];
            $this->is_skill_focus = $skill['player_character_skill_is_skill_focus'];
            $this->weapon_proficiency_id = $skill['player_character_weapon_proficiency_id'];
            $this->weapon2_proficiency_id = $skill['player_character_weapon2_proficiency_id'];
        }

        public function jsonSerialize() {
            return get_object_vars($this);
        }

        public function getPlayerCharacterSkillId() {
            return $this->id;
        }

        public function getSkillCatalogId() {
            return $this->skill_catalog_id;
        }

        public function getPlayerCharacterSkillName() {
            return $this->player_character_skill_name;
        }

        public function getPlayerCharacterSkillCount() {
            return $this->player_character_skill_count;
        }

        public function isSkillFocus() {
            return $this->is_skill_focus;
        }

        public function getWeaponProficiencyId() {
            return $this->weapon_proficiency_id;
        }

        public function getWeapon2ProficiencyId() {
            return $this->weapon2_proficiency_id;
        }
    }
?>
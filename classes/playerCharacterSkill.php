<?php

    class PlayerCharacterSkill implements JsonSerializable
    {
        private $id;
        private $skill_catalog_id;
        private $player_character_skill_name;
        private $is_skill_focus;
        private $weapon_proficiency_id;
        private $weapon2_proficiency_id;
        private $is_preferred_cavalier_level3;
		private $is_preferred_cavalier_level5;
		private $is_preferred_elven_cavalier_level4;
		private $is_preferred_elven_cavalier_level6;

        public function init($skill) {
            $this->id = $skill['player_character_skill_id'];
            $this->skill_catalog_id = $skill['skill_catalog_id'];
            $this->player_character_skill_name = $skill['skill_name'];
            $this->is_skill_focus = $skill['player_character_skill_is_skill_focus'];
            $this->weapon_proficiency_id = $skill['player_character_weapon_proficiency_id'];
            $this->weapon2_proficiency_id = $skill['player_character_weapon2_proficiency_id'];

            $this->is_preferred_cavalier_level3 = $skill['player_character_cavalier_level3_preferred'];
            $this->is_preferred_cavalier_level5 = $skill['player_character_cavalier_level5_preferred'];
            $this->is_preferred_elven_cavalier_level4 = $skill['player_character_elven_cavalier_level4_preferred'];
            $this->is_preferred_elven_cavalier_level6 = $skill['player_character_elven_cavalier_level6_preferred'];
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

        public function isSkillFocus() {
            return $this->is_skill_focus;
        }

        public function getWeaponProficiencyId() {
            return $this->weapon_proficiency_id;
        }

        public function getWeapon2ProficiencyId() {
            return $this->weapon2_proficiency_id;
        }

        public function getIsPreferredCavalierLevel3() {
            return empty($this->is_preferred_cavalier_level3) ? false : true;
        }

        public function getIsPreferredCavalierLevel5() {
            return empty($this->is_preferred_cavalier_level5) ? false : true;
        }

        public function getIsPreferredElvenCavalierLevel4() {
            return empty($this->is_preferred_elven_cavalier_level4) ? false : true;
        }

        public function getIsPreferredElvenCavalierLevel6() {
            return empty($this->is_preferred_elven_cavalier_level6) ? false : true;
        }
    }
?>
<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../webio/weapon2ProficiencyId.php';
    require_once __DIR__ . '/candidateSkill.php';

    class TwoWeaponFighting extends CandidateSkill {
        protected function getSkillId() {
            return TWO_WEAPON_FIGHTING;
        }

        private $weapon_detail;
        public function getWeaponDetail() {
            return $this->weapon_detail;
        }

        public function setWeaponDetail($weapon_detail) {
            $this->weapon_detail = $weapon_detail;
        }

        private $one_handed_weapons;
        public function getOneHandedWeapons() {
            return $this->one_handed_weapons;
        }

        public function setOneHandedWeapons($one_handed_weapons) {
            $this->one_handed_weapons = $one_handed_weapons;
        }

        private $weapon_qualification_satisfied;

        protected function isCharacterQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $base_qualified = parent::isCharacterQualified($character_details, $player_character_skill_set);

            $weapon_melee_satisfied = ($this->weapon_detail->getMeleeWeaponType() == WEAPON_TYPE_MELEE);
            $weapon_hands_satisfied = ($this->weapon_detail->getMeleeWeaponNumberOfHands() == 1);
            $this->weapon_qualification_satisfied = $weapon_melee_satisfied && $weapon_hands_satisfied;

            return $base_qualified && $this->weapon_qualification_satisfied;
        }

        protected function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            $fighter_level = $character_details->getFighterTypeLevel();
            if (empty($fighter_level)) {
                $this->class_and_level_satisfied = false;
            } else {
                $this->class_and_level_satisfied = true;
            }
        }

        protected function renderExistingSkillFields(\PlayerCharacterSkill $skill_instance, \CharacterDetails $character_details) {
            $offhand_description = getWeaponDescriptionFromProficiencyId($skill_instance->getWeapon2ProficiencyId());
            return $skill_instance->getPlayerCharacterSkillName() . ' - 1st: ' . $this->weapon_detail->getWeaponName() . ' / 2nd: ' . $offhand_description;
        }

        protected function renderNewSkillFields($skill_name, \CharacterDetails $character_details) {
            $candidate_weapons = $this->getOffHandWeapon();
            return $skill_name . ' - 1st: ' . $this->weapon_detail->getWeaponName() . ' / 2nd: ' . $candidate_weapons;
        }

        private function getOffHandWeapon() {
            if (count($this->one_handed_weapons) == 1) {
                return $this->one_handed_weapons[0]['weapon_proficiency_name'];
            }

            return $this->buildSelectForCandidateWeapons();
        }

        private function buildSelectForCandidateWeapons() {
            $select_html  = '<select id="offHandWeaponList" onchange="updateOffhandWeaponProficiencyId(';
            $select_html .= "'" . WEAPON2_PROFICIENCY_ID . "', 'offHandWeaponList');";
            $select_html .= '">' . PHP_EOL;
            
            $select_html .= '<option value="-1">[Select a Weapon]</option>' . PHP_EOL;

            foreach($this->one_handed_weapons AS $one_handed_weapon) {
                $select_html .= $this->buildOneHandWeaponOption($one_handed_weapon);
            }
            $select_html .= '</select>' . PHP_EOL;

            return $select_html;
        }

        private function buildOneHandWeaponOption($one_handed_weapon) {
            return '<option value="' . $one_handed_weapon['weapon_proficiency_id'] . '">' . $one_handed_weapon['weapon_proficiency_name'] . '</option>' . PHP_EOL;
        }

         public function dump() {
            $output  = parent::dump();
            $output .= 'Weapon qualification satisfied : ' . var_export($this->weapon_qualification_satisfied, true) . PHP_EOL;

            return $output;
         }
    }
?>
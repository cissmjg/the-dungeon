<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once 'candidateWeaponSkill.php';

    class SharpShooting extends CandidateWeaponSkill {
        protected function getSkillId() {
            return SHARP_SHOOTING;
        }

        private $weapon_detail;
        public function getWeaponDetail() {
            return $this->weapon_detail;
        }

        public function setWeaponDetail($weapon_detail) {
            $this->weapon_detail = $weapon_detail;
        }

        private $character_details;

        protected function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            $this->character_details = $character_details;
            if ($character_details->isArcherType() && $character_details->getFighterTypeLevel() >= 3) {
                $this->class_and_level_satisfied = true;
            } else if ($character_details->getFighterTypeClassId() == FIGHTER && $character_details->getFighterTypeLevel() >= 3) {
                $this->class_and_level_satisfied = true;
            } else {
               $this->class_and_level_satisfied = false;
            }
        }

        protected function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            if ($this->character_details->isArcherType() && isArcherFavoredBowType($this->getWeaponDetail()->getWeaponProficiencyId())) {
                $this->skill_prereq_satisfied = true;
            } else if ($this->character_details->getFighterTypeClassId() == FIGHTER || $this->character_details->isArcherType()) {
                $has_specialization         = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->weapon_detail->getWeaponProficiencyId())) > 0;
                $has_weapon_focus_accuracy  = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_ACCURACY, $this->weapon_detail->getWeaponProficiencyId())) > 0;
                $has_weapon_focus_technique = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_TECHNIQUE, $this->weapon_detail->getWeaponProficiencyId())) > 0;
                $this->skill_prereq_satisfied = ($has_specialization || $has_weapon_focus_accuracy || $has_weapon_focus_technique);
            } else {
                $this->skill_prereq_satisfied = false;
            }

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied;
        }
    }
?>
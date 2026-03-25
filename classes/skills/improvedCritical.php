<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once 'candidateWeaponSkill.php';

    class ImprovedCritical extends CandidateWeaponSkill {
        protected function getSkillId() {
            return IMPROVED_CRITICAL;
        }

        private $weapon_detail;
        public function getWeaponDetail() {
            return $this->weapon_detail;
        }

        public function setWeaponDetail($weapon_detail) {
            $this->weapon_detail = $weapon_detail;
        }

        private $melee_only_satified;

        protected function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            foreach($character_details->getCharacterClasses() as $character_class) {
                if ($character_class->getClassLevel() >= 8) {
                    $this->class_and_level_satisfied = true;
                    return;
                }
            }

           $this->class_and_level_satisfied = false;
        }


        protected function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            $specialization_skill_obtained = in_array(SPECIALIZATION, $player_character_skill_set->getPlayerCharacterSkillIds());
            $weapon_acccuracy_obtained = in_array(WEAPON_FOCUS_ACCURACY, $player_character_skill_set->getPlayerCharacterSkillIds());
            $weapon_technique_obtained = in_array(WEAPON_FOCUS_TECHNIQUE, $player_character_skill_set->getPlayerCharacterSkillIds());

            $this->skill_prereq_satisfied = ($specialization_skill_obtained || $weapon_acccuracy_obtained || $weapon_technique_obtained);

            $this->melee_only_satified = ($this->weapon_detail->getMeleeWeaponType() == WEAPON_TYPE_MELEE);

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied && $this->melee_only_satified;
        }

        public function  dump() {
            $output  = parent::dump();
            $output .= 'Melee only satisfied : ' . var_export($this->melee_only_satified, true) . PHP_EOL;

            return $output;
        }
    }
?>
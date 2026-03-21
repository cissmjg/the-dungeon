<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/candidateSkill.php';

    class WeaponSpecialization extends CandidateSkill {
        protected function getSkillId() {
            return SPECIALIZATION;
        }

        private $weapon_detail;
        public function getWeaponDetail() {
            return $this->weapon_detail;
        }

        public function setWeaponDetail($weapon_detail) {
            $this->weapon_detail = $weapon_detail;
        }

        private $class_count_satisfied;

        private $archer_melee_only_satisfied;

        public function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            $fighter_level = $character_details->getFighterTypeLevel();
            if (empty($fighter_level)) {
                $this->class_and_level_satisfied = false;
                return;
            }

            // Archer(-Ranger) Melee only
            $this->archer_melee_only_satisfied = true;
            if ($character_details->containsClassId(ARCHER) || $character_details->containsClassId(ARCHER_RANGER)) {
                if($this->weapon_detail->getMeleeWeaponType == WEAPON_TYPE_MELEE) {
                    $this->archer_melee_only_satisfied = true;
                } else {
                    $this->archer_melee_only_satisfied = false;
                }
            }

            // Single class Fighter type only
            $this->class_count_satisfied = ($character_details->classCount() == 1);

            $this->class_and_level_satisfied = ($fighter_level >= 4) && $this->class_count_satisfied && $this->archer_melee_only_satisfied;
        }

         public function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            $weapon_focus_accuracy_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_ACCURACY, $this->getWeaponProficiencyValue())) > 0 ? true : false;
            $weapon_focus_technique_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_TECHNIQUE, $this->getWeaponProficiencyValue())) > 0 ? true : false;

            if ($weapon_focus_accuracy_obtained || $weapon_focus_technique_obtained) {
                $this->skill_prereq_satisfied = false;
            } else {
                $this->skill_prereq_satisfied = true;
            }

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied;
         }

         public function dump() {
            $output  = parent::dump();
            $output .= 'Class count satisfied : ' . var_export($this->class_count_satisfied, true) . PHP_EOL;
            $output .= 'Archer_melee_only_satisfied : ' . var_export($this->archer_melee_only_satisfied, true) . PHP_EOL;

            return $output;
         }
    }
?>
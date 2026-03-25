<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class WeaponFocusAccuracy extends CandidateWeaponSkill {
        protected function getSkillId() {
            return WEAPON_FOCUS_ACCURACY;
        }

        private $restricted_skills_satisfied;
        private $specialization_obtained;
        private $double_specialization_obtained;

        public function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = parent::skillCountSatisfied($player_character_skill_set, $skill_detail);

            $this->skill_prereq_satisfied = $this->isSubset($skill_detail->getPrerequisiteSKillIds(), $player_character_skill_set->getPlayerCharacterSkillIds());

            $this->specialization_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->getWeaponProficiencyValue())) > 0 ? true : false;
            $this->double_specialization_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(DOUBLE_SPECIALIZATION, $this->getWeaponProficiencyValue())) > 0 ? true : false;

            if ($this->specialization_obtained || $this->double_specialization_obtained) {
                $this->restricted_skills_satisfied = false;
            } else {
                $this->restricted_skills_satisfied = true;
            }

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied && $this->restricted_skills_satisfied;
        }

        public function dump() {
            $output  = parent::dump();
            $output .= 'Specialization obtained : ' . var_export($this->specialization_obtained, true) . PHP_EOL;
            $output .= 'Double Specialization obtained : ' . var_export($this->double_specialization_obtained, true) . PHP_EOL;
            $output .= 'Restricted skills satisfied : ' . var_export($this->restricted_skills_satisfied, true) . PHP_EOL;
            
            return $output;
        }
    }
?>
<?php
    require_once 'candidateSkill.php';
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';

    class AgileAthlete extends CandidateSkill {
        protected function getSkillId() {
            return AGILE_ATHLETE;
        }

        private $has_jump_skill;
        private $class_has_jump_skill;
        private $has_jump;
        private $has_climb_skill;
        private $class_has_climb_skill;
        private $has_climb;
        private $base_satisfied;

        protected function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->base_satisfied = parent::prerequisiteSkillsSatisfied($player_character_skill_set, $skill_detail);
            if (!$this->base_satisfied) {
                return false;
            }

            $has_jump_instances = $player_character_skill_set->getAllSkillInstances(JUMP);
            $this->has_jump_skill = count($has_jump_instances) > 0;

            $this->class_has_jump_skill = $this->class_has_jump($this->character_details);
            $this->has_jump = $this->has_jump_skill || $this->class_has_jump_skill;

            $has_climb_instances = $player_character_skill_set->getAllSkillInstances(CLIMB);
            $this->has_climb_skill = count($has_climb_instances) > 0;

            $this->class_has_climb_skill = $this->class_has_climb($this->character_details);
            $this->has_climb = $this->has_climb_skill || $this->class_has_climb_skill;

            return $this->base_satisfied && ($this->has_jump || $this->has_climb);
        }

        private function class_has_jump(CharacterDetails $character_details) {
            $class_ids_that_jump = [THIEF_ACROBAT, NINJA];
            foreach($character_details->getCharacterClasses() AS $account_class_summary) {
                if (in_array($account_class_summary->getClassId(), $class_ids_that_jump)) {
                    return true;
                }
            }

            return false;
        }

        private function class_has_climb(CharacterDetails $character_details) {
            $class_ids_that_climb = [THIEF, MONK, BARBARIAN, THIEF_ACROBAT, ORIENTAL_BARBARIAN, OA_MONK, NINJA, NEW_MONK, BANDIT];
            foreach($character_details->getCharacterClasses() AS $account_class_summary) {
                if (in_array($account_class_summary->getClassId(), $class_ids_that_climb)) {
                    return true;
                }
            }

            $assassin_has_climb_walls = $character_details->containsClassId(ASSASSIN) && $character_details->getLevelForClass(ASSASSIN) >= 3;
            return $assassin_has_climb_walls;
        }

        public function dump() {
            $output  = parent::dump();
            $output .= 'Base satisfied : ' . var_export($this->base_satisfied, true) . PHP_EOL;
            $output .= 'Has Jump skill : ' . var_export($this->has_jump_skill, true) . PHP_EOL; 
            $output .= 'Class has Jump skill : ' . var_export($this->class_has_jump_skill, true) . PHP_EOL; 
            $output .= 'Has Jump : ' . var_export($this->has_jump, true) . PHP_EOL; 
            $output .= 'Has Climb skill : ' . var_export($this->has_climb_skill, true) . PHP_EOL; 
            $output .= 'Class has Climb skill : ' . var_export($this->class_has_climb_skill, true) . PHP_EOL; 
            $output .= 'Has Climb : ' . var_export($this->has_climb, true) . PHP_EOL;

            return $output;
        }
    }
?>

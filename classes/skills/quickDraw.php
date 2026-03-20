<?php

    class QuickDraw extends CandidateSkill {
        protected function getSkillId() {
            return QUICK_DRAW;
        }

         protected function skillCountSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $weapon_proficiency_id = $this->getWeaponProficiencyValue();
            $all_quickdraw_weapons = $player_character_skill_set->getAllSkillInstances(QUICK_DRAW);
            foreach($all_quickdraw_weapons AS $quickdraw_weapon) {
                if ($quickdraw_weapon->getWeaponProficiencyId() == $weapon_proficiency_id) {
                    return false;
                }
            }

           return true;
         }
    }
?>
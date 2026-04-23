<?php
    require_once 'candidateWeaponSkill.php';

    class FistOfIron extends CandidateWeaponSkill {
        protected function getSkillId() {
            return FIST_OF_IRON;
        }

        public static function formatFistOfIronDamage($count_skill_instance) {
            return sprintf("+%dd4", $count_skill_instance);
        }
    }
?>
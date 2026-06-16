<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class CleverWrestling extends CandidateWeaponSkill {
        protected function getSkillId() {
            return CLEVER_WRESTLING;
        }
    }
?>
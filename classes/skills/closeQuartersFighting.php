<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class CloseQuarterFighting extends CandidateWeaponSkill {
        protected function getSkillId() {
            return CLOSE_QUARTERS_FIGHTING;
        }
    }
?>
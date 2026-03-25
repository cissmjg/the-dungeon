<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class DirtyFighting extends CandidateWeaponSkill {
        protected function getSkillId() {
            return DIRTY_FIGHTING;
        }
    }
?>
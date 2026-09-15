<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class MartialArts extends CandidateWeaponSkill {
        protected function getSkillId() {
            return MARTIAL_ARTS;
        }
    }
?>
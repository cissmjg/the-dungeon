<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class ThrowAnything extends CandidateWeaponSkill {
        protected function getSkillId() {
            return THROW_ANYTHING;
        }
    }
?>
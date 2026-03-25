<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateSkill.php';

    class WeaponFinesse extends CandidateSkill {
        protected function getSkillId() {
            return WEAPON_FINESSE;
        }
    }
?>
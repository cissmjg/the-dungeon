<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class EagleClaw extends CandidateWeaponSkill {
        protected function getSkillId() {
            return EAGLE_CLAW;
        }
    }
?>
<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class CircleKick extends CandidateWeaponSkill {
        protected function getSkillId() {
            return CIRCLE_KICK;
        }
    }
?>
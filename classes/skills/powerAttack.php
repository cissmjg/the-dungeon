<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateSkill.php';

    class PowerAttack extends CandidateSkill {
        protected function getSkillId() {
            return POWER_ATTACK;
        }
    }
?>
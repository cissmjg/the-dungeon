<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateSkill.php';

    class PreciseShot extends CandidateSkill {
        protected function getSkillId() {
            return PRECISE_SHOT;
        }
    }
?>
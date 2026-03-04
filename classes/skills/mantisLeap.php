<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/candidateSkill.php';

    class MantisLeap extends CandidateSkill {
        protected function getSkillId() {
            return MANTIS_LEAP;
        }
    }
?>
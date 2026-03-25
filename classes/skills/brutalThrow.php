<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateSkill.php';

    class BrutalThrow extends CandidateSkill {
        protected function getSkillId() {
            return BRUTAL_THROW;
        }
    }
?>
<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateSkill.php';

    class ZenArchery extends CandidateSkill {
        protected function getSkillId() {
            return ZEN_ARCHERY;
        }
    }
?>
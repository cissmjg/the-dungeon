<?php

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/candidateSkill.php';

    class DirtyFighting extends CandidateSkill {
        protected function getSkillId() {
            return DIRTY_FIGHTING;
        }
    }
?>
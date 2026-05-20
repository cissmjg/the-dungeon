<?php
    require_once 'candidateSkill.php';
    require_once __DIR__ . '/../../dbio/constants/skills.php';

    class RapidReload extends CandidateSkill {
        protected function getSkillId() {
            return RAPID_RELOAD;
        }
    }
?>
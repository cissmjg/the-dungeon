<?php
    require_once 'candidateSkill.php';

    class MoveSilently extends CandidateSkill {
        protected function getSkillId() {
            return MOVE_SILENTLY;
        }
    }
?>
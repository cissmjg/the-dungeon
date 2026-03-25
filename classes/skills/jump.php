<?php
    require_once 'candidateSkill.php';

    class Jump extends CandidateSkill {
        protected function getSkillId() {
            return JUMP;
        }
    }
?>
<?php
    require_once 'candidateSkill.php';

    class Stealthy extends CandidateSkill {
        protected function getSkillId() {
            return STEALTHY;
        }
    }
?>
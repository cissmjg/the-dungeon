<?php
    require_once 'candidateSkill.php';

    class Climb extends CandidateSkill {
        protected function getSkillId() {
            return CLIMB;
        }
    }
?>
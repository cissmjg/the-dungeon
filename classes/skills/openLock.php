<?php
    require_once 'candidateSkill.php';

    class OpenLock extends CandidateSkill {
        protected function getSkillId() {
            return OPEN_LOCK;
        }
    }
?>
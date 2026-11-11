<?php
    require_once 'candidateSkill.php';

    class DisableDevice extends CandidateSkill {
        protected function getSkillId() {
            return DISABLE_DEVICE;
        }
    }
?>
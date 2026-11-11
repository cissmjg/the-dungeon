<?php
    require_once 'candidateSkill.php';

    class PickPocket extends CandidateSkill {
        protected function getSkillId() {
            return PICK_POCKET;
        }
    }
?>
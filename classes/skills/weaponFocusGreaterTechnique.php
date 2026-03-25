<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSubtype.php';

    class WeaponFocusGreaterTechnique extends CandidateWeaponSkill {
        protected function getSkillId() {
            return WEAPON_FOCUS_GREATER_TECHNIQUE;
        }

        private $weapon_detail;
        public function getWeaponDetail() {
            return $this->weapon_detail;
        }

        public function setWeaponDetail($weapon_detail) {
            $this->weapon_detail = $weapon_detail;
        }

        private $weapon_type_satisfied;
        private $weapon_subtype_satisfied;
        private $specialization_obtained;
        private $double_specialization_obtained;
        private $restricted_skills_satisfied;

         public function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            $this->skill_prereq_satisfied = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_TECHNIQUE, $this->getWeaponProficiencyValue())) > 0 ? true : false;
            
            $this->weapon_type_satisfied = $this->weapon_detail->getMeleeWeaponType() == WEAPON_TYPE_MELEE ? true : false;
            
            $weapon_subtype = $this->weapon_detail->getMeleeWeaponSubtype();
            if ($weapon_subtype == WEAPON_SUBTYPE_TWO_HANDED_SWORD || $weapon_subtype == WEAPON_SUBTYPE_POLE_ARM) {
                $this->weapon_subtype_satisfied = false;
            } else {
                $this->weapon_subtype_satisfied = true;
            }

            $this->specialization_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->getWeaponProficiencyValue())) > 0 ? true : false;
            $this->double_specialization_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(DOUBLE_SPECIALIZATION, $this->getWeaponProficiencyValue())) > 0 ? true : false;

            if ($this->specialization_obtained || $this->double_specialization_obtained) {
                $this->restricted_skills_satisfied = false;
            } else {
                $this->restricted_skills_satisfied = true;
            }

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied && $this->weapon_type_satisfied && $this->weapon_subtype_satisfied && $this->restricted_skills_satisfied;
        }

        public function dump() {
            $output  = parent::dump();
            $output .= 'Weapon Type (Melee) : ' . getWeaponTypeDescription($this->weapon_detail->getMeleeWeaponType()) . PHP_EOL;
            $output .= 'Weapon Type (Missile) : ' . getWeaponTypeDescription($this->weapon_detail->getMissileWeaponType()) . PHP_EOL;
            $output .= 'Weapon Type (Melee) : ' . getWeaponSubtypeDescription($this->weapon_detail->getMeleeWeaponSubtype()) . PHP_EOL;
            $output .= 'Weapon Type (Missile) : ' . getWeaponSubtypeDescription($this->weapon_detail->getMissileWeaponSubtype()) . PHP_EOL;
            $output .= 'Weapon Type satisfied : ' . var_export($this->weapon_type_satisfied, true) . PHP_EOL;
            $output .= 'Weapon Subtype satisfied : ' . var_export($this->weapon_subtype_satisfied, true) . PHP_EOL;
            $output .= 'Specialization obtained : ' . var_export($this->specialization_obtained, true) . PHP_EOL;
            $output .= 'Double Specialization obtained : ' . var_export($this->double_specialization_obtained, true) . PHP_EOL;
            $output .= 'Restricted skills satisfied : ' . var_export($this->restricted_skills_satisfied, true) . PHP_EOL;

            return $output;
        }
    }
?>
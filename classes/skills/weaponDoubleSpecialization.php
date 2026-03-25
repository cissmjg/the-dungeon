<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSubtype.php';

    class WeaponDoubleSpecialization extends CandidateWeaponSkill {
        protected function getSkillId() {
            return DOUBLE_SPECIALIZATION;
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
        private $class_count_satisfied;
        private $archer_melee_only_satisfied;

        public function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            $fighter_level = $character_details->getFighterTypeLevel();
            if (empty($fighter_level)) {
                $this->class_and_level_satisfied = false;
                return;
            }

            // Archer(-Ranger) Melee only
            $this->archer_melee_only_satisfied = true;
            if ($character_details->containsClassId(ARCHER) || $character_details->containsClassId(ARCHER_RANGER)) {
                if($this->weapon_detail->getMeleeWeaponType == WEAPON_TYPE_MELEE) {
                    $this->archer_melee_only_satisfied = true;
                } else {
                    $this->archer_melee_only_satisfied = false;
                }
            }

            // Single class Fighter type only
            $this->class_count_satisfied = ($character_details->classCount() == 1);

            $this->class_and_level_satisfied = ($fighter_level >= 7) && $this->class_count_satisfied && $this->archer_melee_only_satisfied;
        }

         public function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            // Check prerequisite skill for same weapon Proficiency
            $this->skill_prereq_satisfied = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->getWeaponProficiencyValue())) > 0 ? true : false;
            
            $this->weapon_type_satisfied = $this->weapon_detail->getMeleeWeaponType() == WEAPON_TYPE_MELEE ? true : false;
            
            $weapon_subtype = $this->weapon_detail->getMeleeWeaponSubtype();
            if ($weapon_subtype == WEAPON_SUBTYPE_TWO_HANDED_SWORD || $weapon_subtype == WEAPON_SUBTYPE_POLE_ARM) {
                $this->weapon_subtype_satisfied = false;
            } else {
                $this->weapon_subtype_satisfied = true;
            }

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied && $this->weapon_type_satisfied && $this->weapon_subtype_satisfied;
        }

        public function dump() {
            $output  = parent::dump();
            $output .= 'Weapon Type (Melee) : ' . getWeaponTypeDescription($this->weapon_detail->getMeleeWeaponType()) . PHP_EOL;
            $output .= 'Weapon Type (Missile) : ' . getWeaponTypeDescription($this->weapon_detail->getMissileWeaponType()) . PHP_EOL;
            $output .= 'Weapon Subtype (Melee) : ' . getWeaponSubtypeDescription($this->weapon_detail->getMeleeWeaponSubtype()) . PHP_EOL;
            $output .= 'Weapon Subtype (Missile) : ' . getWeaponSubtypeDescription($this->weapon_detail->getMissileWeaponSubtype()) . PHP_EOL;
            $output .= 'Weapon Type satisfied : ' . var_export($this->weapon_type_satisfied, true) . PHP_EOL;
            $output .= 'Weapon Subtype satisfied : ' . var_export($this->weapon_subtype_satisfied, true) . PHP_EOL;
            $output .= 'Class count satisfied : ' . var_export($this->class_count_satisfied, true) . PHP_EOL;
            $output .= 'Archer_melee_only_satisfied : ' . var_export($this->archer_melee_only_satisfied, true) . PHP_EOL;

            return $output;
        }
    }
?>
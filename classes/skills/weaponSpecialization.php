<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSpecializationType.php';

    require_once 'candidateWeaponSkill.php';

    class WeaponSpecialization extends CandidateWeaponSkill {
        protected function getSkillId() {
            return SPECIALIZATION;
        }

        private $weapon_detail;
        public function getWeaponDetail() {
            return $this->weapon_detail;
        }

        public function setWeaponDetail($weapon_detail) {
            $this->weapon_detail = $weapon_detail;
        }

        private $class_count_satisfied;

        private $archer_melee_only_satisfied;

        public function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            if ($character_details->containsClassId(BARBARIAN)) {
                $this->class_and_level_satisfied = false;
                return;
            }

            $fighter_level = $character_details->getFighterTypeLevel();
            if (empty($fighter_level)) {
                $this->class_and_level_satisfied = false;
                return;
            }

            // Archer(-Ranger) Melee only
            $this->archer_melee_only_satisfied = true;
            if ($character_details->containsClassId(ARCHER) || $character_details->containsClassId(ARCHER_RANGER)) {
                if($this->weapon_detail->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
                    $this->archer_melee_only_satisfied = true;
                } else {
                    $this->archer_melee_only_satisfied = false;
                }
            }

            // Single class Fighter type only
            $this->class_count_satisfied = ($character_details->classCount() == 1);

            $this->class_and_level_satisfied = ($fighter_level >= 4) && $this->class_count_satisfied && $this->archer_melee_only_satisfied;
        }

         public function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            $weapon_focus_accuracy_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_ACCURACY, $this->getWeaponProficiencyValue())) > 0 ? true : false;
            $weapon_focus_technique_obtained = count($player_character_skill_set->getAllSkillInstancesForWeapon(WEAPON_FOCUS_TECHNIQUE, $this->getWeaponProficiencyValue())) > 0 ? true : false;

            if ($weapon_focus_accuracy_obtained || $weapon_focus_technique_obtained) {
                $this->skill_prereq_satisfied = false;
            } else {
                $this->skill_prereq_satisfied = true;
            }

            return $this->skill_count_satisfied && $this->skill_prereq_satisfied;
         }

         public function renderNewSkillFields($skill_name, \CharacterDetails $character_details) {
            if ($this->getWeaponDetail()->isCombinationWeapon()) {
                return $this->buildUpdateWeaponSpecializationTypeDropdown($skill_name);
            } else {
                $skill_specialization = '';
                if ($this->weapon_detail->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
                    $skill_specialization = WeaponSpecializationType::Melee->getDescription();
                } else if ($this->getWeaponDetail()->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                    $skill_specialization = WeaponSpecializationType::Missile->getDescription();
                }
                
                return sprintf("%s: %s", $skill_name, $skill_specialization);
            }
         }

         private function buildUpdateWeaponSpecializationTypeDropdown($skill_name) {
            $output_html  = $skill_name . '&nbsp;';
            $weapon_specialization_type_element_id = "'" . $this->formIdLookup->getAddWeaponSpecializationTypeElementId() . "'";
            $weapon_specialization_type_select_element = 'this';

            $output_html .= '<select id="updateWeaponSpecializationType" onchange="updateWeaponSpecializationTypeId(' . $weapon_specialization_type_select_element . ', ' . $weapon_specialization_type_element_id . ', ' . WeaponSpecializationType::None->value . ');">' . PHP_EOL;
            $output_html .= '<option value="' . WeaponSpecializationType::None->value . '">[Select a Specialization Type]</option>' . PHP_EOL;
            $output_html .= '<option value="' . WeaponSpecializationType::Melee->value . '">' . WeaponSpecializationType::Melee->getDescription() . '</option>' . PHP_EOL;
            $output_html .= '<option value="' . WeaponSpecializationType::Missile->value . '">' . WeaponSpecializationType::Missile->getDescription() . '</option>' . PHP_EOL;
            $output_html .= '</select>' . PHP_EOL;

            return $output_html;
         }

         protected function renderExistingSkillFields(\PlayerCharacterSkill $skill_instance, \CharacterDetails $character_details) {
            $skill_name = $skill_instance->getPlayerCharacterSkillName();
            $skill_specialization = $skill_instance->getWeaponSpecializationType()->getDescription();

            return sprintf("%s: %s", $skill_name, $skill_specialization);
         }

        protected function buildAddPlayerCharacterWeaponTalentIcon($add_form_id, $skill_catalog_element_id, $skill_catalog_value, $weapon2_element_id) {
            if ($this->weapon_detail->isCombinationWeapon()) {
                $new_icon = new FaNewIcon();
                $weapon_specialization_type_select_element = 'this';
                $weapon_specialization_type_element_id = $this->formIdLookup->getAddWeaponSpecializationTypeElementId();
                $weapon_specialization_type_none = WeaponSpecializationType::None->value;

                $new_icon->setOnClickJsFunction('addWeaponSpecializationSkill');
                $new_icon->addOnclickJsParameter($add_form_id);
                $new_icon->addOnclickJsParameter($skill_catalog_element_id);
                $new_icon->addOnclickJsParameter($skill_catalog_value);
                $new_icon->addOnclickJsParameter($weapon2_element_id);
                $new_icon->addUnquotedOnclickJsParameter($weapon_specialization_type_select_element);
                $new_icon->addOnclickJsParameter($weapon_specialization_type_element_id);
                $new_icon->addOnclickJsParameter($weapon_specialization_type_none);
                $new_icon->addStyle("padding-right: 10px;");
                $new_icon->addStyle("padding-left: 5px;");
                return $new_icon->build();
            } else {
                return parent::buildAddPlayerCharacterWeaponTalentIcon($add_form_id, $skill_catalog_element_id, $skill_catalog_value, $weapon2_element_id);
            }
        }

         public function dump() {
            $output  = parent::dump();
            $output .= 'Class count satisfied : ' . var_export($this->class_count_satisfied, true) . PHP_EOL;
            $output .= 'Archer_melee_only_satisfied : ' . var_export($this->archer_melee_only_satisfied, true) . PHP_EOL;

            return $output;
         }
    }
?>
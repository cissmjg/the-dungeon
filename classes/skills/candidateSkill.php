<?php
    require_once '/../../dbio/constants/characterClasses.php';
    require_once '/../../fa/faNewIcon.php';
    require_once '/../../fa/faDeleteIcon.php';

    abstract class CandidateSkill {
        protected $skill;

        protected $delete_form_id;

        protected $delete_weapon_talent_id;

        protected $add_form_id;

        protected $add_skill_catalog_id;

        protected $add_weapon_proficiency_id;

        protected $add_weapon2_proficiency_id;

        protected $add_weapon_proficiency_value;
        public function getWeaponProficiencyValue() {
            return $this->add_weapon_proficiency_value;
        }

        public function setWeaponProficiencyValue($weapon_proficiency_value) {
            $this->add_weapon_proficiency_value = $weapon_proficiency_value;
        }

        protected $add_weapon2_proficiency_value;
        public function getWeapon2ProficiencyValue() {
            return $this->add_weapon2_proficiency_value;
        }

        public function setWeapon2ProficiencyValue($weapon_proficiency_value) {
            $this->add_weapon2_proficiency_value = $weapon_proficiency_value;
        }
        
        abstract protected function getSkillId();
        
        public function __construct(\SkillCatalog $skill_catalog, $delete_form_id, $delete_weapon_talent_id, $add_form_id, $add_skill_catalog_id, $add_weapon_proficiency_id, $add_weapon2_proficiency_id) {
            $skill_id = $this->getSkillId();
            $this->skill = $skill_catalog->getSkill($skill_id);

            $this->delete_form_id = $delete_form_id;
            $this->delete_weapon_talent_id = $delete_weapon_talent_id;
            $this->add_form_id = $add_form_id;
            $this->add_skill_catalog_id = $add_skill_catalog_id;
            $this->add_weapon_proficiency_id = $add_weapon_proficiency_id;
            $this->add_weapon2_proficiency_id = $add_weapon2_proficiency_id;
        }
        
        private function isCharacterQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $base_attributes_satisfied = $this->baseAttributesQualified($character_details, $player_character_skill_set, $this->skill);
            $prerequisite_skills_satisfied = $this->prerequisiteSkillsSatisfied($player_character_skill_set, $this->skill);
            
            return $base_attributes_satisfied && $prerequisite_skills_satisfied;
        }
        
        protected function baseAttributesQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skillDetail) {
            $charisma_satisfied = true;
            if (!empty($skillDetail->getMinCharisma())) {
                if ($character_details->getCharacterCharisma() < $skillDetail->getMinCharisma()) {
                    $charisma_satisfied = false;
                }
            }
            
            $dexterity_satified = true;
            if (!empty($skillDetail->getMinDexterity())) {
                if ($character_details->getCharacterDexterity() < $skillDetail->getMinDexterity()) {
                    $dexterity_satified = false;
                }
            }
            
            $intelligence_satisfied = true;
            if (!empty($skillDetail->getMinIntelligence())) {
                if ($character_details->getCharacterIntelligence() < $skillDetail->getMinIntelligence()) {
                    $intelligence_satisfied = false;
                }
            }
            
            $race_satisfied = $this->raceSatisifed($skillDetail, $character_details);
            $class_and_level_satisfied = $this->classAndLevelSatisfied($skillDetail, $character_details);
            
            return $charisma_satisfied && $dexterity_satified && $intelligence_satisfied && $race_satisfied && $class_and_level_satisfied;
        }
        
        protected function raceSatisifed(\SkillDetail $skillDetail, \CharacterDetails $character_details) {
            if (empty($skillDetail->getRequireRaceId())) {
                return true;
            }
            
            return ($skillDetail->getRequireRaceId() == $character_details->getRaceId());
        }
        
        protected function classAndLevelSatisfied(\SkillDetail $skillDetail, \CharacterDetails $character_details) {
            if (empty($skillDetail->getRequiredClassId())) {
                return true;
            }
            
            if (!$character_details->containsClassId($skillDetail->getRequiredClassId())) {
                return false;
            }
            
            return ($character_details->levelForClassId($skillDetail->getRequiredClassId()) >= $skillDetail->getRequiredLevel());
        }
        
        protected function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skillDetail) {
            $skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skillDetail);

            $skill_prereq_satisfied = $this->isSubset($skillDetail->getPrerequisiteSKillIds(), $player_character_skill_set->getPlayerCharacterSkillIds());
            
            return $skill_count_satisfied && $skill_prereq_satisfied;
        }
        
        private function skillCountSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skillDetail) {
            $max_count_satisfied = true;
            if($skillDetail->getMaxCount() != -1) {
                
                $skill_count = 0;
                $player_character_skill_detail = $player_character_skill_set->getSkill($skillDetail->getSkillId());
                if (!empty($player_character_skill_detail)) {
                    $skill_count = $player_character_skill_detail->getPlayerCharacterSkillCount();
                }

                $max_count_satisfied = ($skill_count < $skillDetail->getMaxCount());
            }
            
            return $max_count_satisfied;
        }
        
        private function isSubset(array $subset, array $superset): bool {
            // array_diff returns elements in $subset not found in $superset
            return empty(array_diff($subset, $superset));
        }

        protected function isCharacterFighterType(\CharacterDetails $character_details) {
            if ($character_details->containsClassId(FIGHTER)) {
                return true;
            }

            if ($character_details->containsClassId(RANGER)) {
                return true;
            }

            if ($character_details->containsClassId(ARCHER)) {
                return true;
            }

            if ($character_details->containsClassId(ARCHER_RANGER)) {
                return true;
            }

            if ($character_details->containsClassId(BERSERKER)) {
                return true;
            }

            if ($character_details->containsClassId(MARINER)) {
                return true;
            }

            if ($character_details->containsClassId(SENTINAL)) {
                return true;
            }

            return false;
        }

        public function render(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $qualified =  $this->isCharacterQualified($character_details, $player_character_skill_set);
            $output_html = '';
            if($qualified) {
                $output_html  = '<div>' . PHP_EOL;
                $output_html .= $this->renderExistingSkillInstances($character_details, $player_character_skill_set);
                $output_html .= '<hr>';
                $output_html .= $this->renderNewSkillInstance($character_details, $player_character_skill_set);
                $output_html .= '</div>' . PHP_EOL;
            }

            return $output_html;
        }

        private function renderExistingSkillInstances(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $skill_id = $this->getSkillId();
            $all_skill_instances = $player_character_skill_set->getAllSkillInstances($skill_id);

            $output_html = '';
            if (!empty($all_skill_instances)) {
                $output_html .= '<div>' . PHP_EOL;
                foreach($all_skill_instances AS $skill_instance) {
                    $output_html .= $this->formatExistingSkillInstance($skill_instance);
                }
                $output_html .= '</div>' . PHP_EOL;
            }

            return $output_html;
        }

        function formatExistingSkillInstance(\PlayerCharacterSkill $skill_instance) {
            $skill_name =  str_replace("'", "", html_entity_decode($skill_instance->getPlayerCharacterSkillName()));
            $form_id = $this->delete_form_id;
            $talent_id = $this->delete_weapon_talent_id;
            $player_character_talent_id = $skill_instance->getPlayerCharacterSkillId();

            $output_html  = '    <div>';
            $output_html .= $this->buildDeletePlayerCharacterWeaponTalentIcon($form_id, $talent_id, $player_character_talent_id, $skill_name);
            $output_html .= $skill_instance->getPlayerCharacterSkillName();
            $output_html .= '</div>' . PHP_EOL;

            return $output_html;
        }

        private function buildDeletePlayerCharacterWeaponTalentIcon($delete_form_id, $delete_talent_id, $player_character_weapon_talent_id, $weapon_talent_desc) {
            $delete_icon = new FaDeleteIcon();
            $delete_icon->setOnClickJsFunction('confirmPlayerCharacterWeaponTalentDelete');
            $delete_icon->addOnclickJsParameter($delete_form_id);
            $delete_icon->addOnclickJsParameter($delete_talent_id);
            $delete_icon->addOnclickJsParameter($player_character_weapon_talent_id);
            $delete_icon->addOnclickJsParameter($weapon_talent_desc);
            $delete_icon->setHoverText('Delete ' . $weapon_talent_desc);

            return $delete_icon->build();
        }

        private function renderNewSkillInstance(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $skill_name = $this->skill->getSkillName();
            $form_id = $this->add_form_id;
            $skill_catalog_element_id = $this->add_skill_catalog_id;
            $skill_catalog_value = $this->getSkillId();
            $weapon_proficiency_id = $this->add_weapon_proficiency_id;
            $weapon2_proficiency_id = $this->add_weapon2_proficiency_id;

            $weapon_proficiency_value = $this->getWeaponProficiencyValue();
            $weapon2_proficiency_value = $this->getWeapon2ProficiencyValue();

            $output_html = '';
            $output_html  = '    <div>';
            $output_html .= $this->buildAddPlayerCharacterWeaponTalentIcon($form_id, $skill_catalog_element_id, $skill_catalog_value, $weapon_proficiency_id, $weapon_proficiency_value, $weapon2_proficiency_id, $weapon2_proficiency_value);
            $output_html .= $skill_name;
            $output_html .= '</div>' . PHP_EOL;
            return $output_html;
        }

        private function buildAddPlayerCharacterWeaponTalentIcon($add_form_id, $skill_catalog_element_id, $skill_catalog_value, $weapon_talent_element_id, $weapon_talent_element_value, $weapon2_talent_element_id, $weapon2_talent_element_value) {
            $new_icon = new FaNewIcon();
            $new_icon->setOnClickJsFunction('submitAddWeaponTalentForm');
            $new_icon->addOnclickJsParameter($add_form_id);
            $new_icon->addOnclickJsParameter($skill_catalog_element_id);
            $new_icon->addOnclickJsParameter($skill_catalog_value);
            $new_icon->addOnclickJsParameter($weapon_talent_element_id);
            $new_icon->addOnclickJsParameter($weapon_talent_element_value);
            $new_icon->addOnclickJsParameter($weapon2_talent_element_id);
            $new_icon->addOnclickJsParameter($weapon2_talent_element_value);

            return $new_icon->build();
        }
    }
    ?>
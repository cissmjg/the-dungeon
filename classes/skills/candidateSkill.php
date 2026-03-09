<?php
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../fa/faNewIcon.php';
    require_once __DIR__ . '/../../fa/faDeleteIcon.php';
    require_once __DIR__ . '/formIdLookup.php';

    abstract class CandidateSkill {
        protected $skill;
        protected $formIdLookup;

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

        // Debug variables
        private $charisma_satisfied;
        private $dexterity_satisfied;
        private $intelligence_satisfied;
        private $race_satisfied;
        private $class_and_level_satisfied;
        private $skill_count_satisfied;
        private $skill_prereq_satisfied;
        private $qualified;
        private $count_skill_instances = -1;

        // REMOVE
        private $player_character_skill_set;

        public function __construct(\SkillCatalog $skill_catalog, \FormIdLookup $formIdLookup) {
            $skill_id = $this->getSkillId();
            $this->skill = $skill_catalog->getSkill($skill_id);

            $this->formIdLookup = $formIdLookup;
        }
        
        private function isCharacterQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $this->player_character_skill_set = $player_character_skill_set;
            $base_attributes_satisfied = $this->baseAttributesQualified($character_details, $player_character_skill_set, $this->skill);
            $prerequisite_skills_satisfied = $this->prerequisiteSkillsSatisfied($player_character_skill_set, $this->skill);
            
            return $base_attributes_satisfied && $prerequisite_skills_satisfied;
        }
        
        protected function baseAttributesQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->charisma_satisfied = true;
            if (!empty($skill_detail->getMinCharisma())) {
                if ($character_details->getCharacterCharisma() < $skill_detail->getMinCharisma()) {
                    $this->charisma_satisfied = false;
                }
            }
            
            $this->dexterity_satisfied = true;
            if (!empty($skill_detail->getMinDexterity())) {
                if ($character_details->getCharacterDexterity() < $skill_detail->getMinDexterity()) {
                    $this->dexterity_satisfied = false;
                }
            }
            
            $this->intelligence_satisfied = true;
            if (!empty($skill_detail->getMinIntelligence())) {
                if ($character_details->getCharacterIntelligence() < $skill_detail->getMinIntelligence()) {
                    $this->intelligence_satisfied = false;
                }
            }
            
            $this->raceSatisifed($skill_detail, $character_details);
            $this->classAndLevelSatisfied($skill_detail, $character_details);
            
            return $this->charisma_satisfied && $this->dexterity_satisfied && $this->intelligence_satisfied && $this->race_satisfied && $this->class_and_level_satisfied;
        }
        
        protected function raceSatisifed(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            if (empty($skill_detail->getRequireRaceId())) {
                $this->race_satisfied = true;
            } else {
                $this->race_satisfied = ($skill_detail->getRequireRaceId() == $character_details->getRaceId()) ? "yes" : "no";
            }
        }
        
        protected function classAndLevelSatisfied(\SkillDetail $skill_detail, \CharacterDetails $character_details) {
            if (empty($skill_detail->getRequiredClassId())) {
                $this->class_and_level_satisfied = true;
            } else if (!$character_details->containsClassId($skill_detail->getRequiredClassId())) {
                $this->class_and_level_satisfied = false;
            } else {
                $this->class_and_level_satisfied = ($character_details->levelForClassId($skill_detail->getRequiredClassId()) >= $skill_detail->getRequiredLevel());
            }
        }
        
        protected function prerequisiteSkillsSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $this->skill_count_satisfied = $this->skillCountSatisfied($player_character_skill_set, $skill_detail);

            $this->skill_prereq_satisfied = $this->isSubset($skill_detail->getPrerequisiteSKillIds(), $player_character_skill_set->getPlayerCharacterSkillIds());
            
            return $this->skill_count_satisfied && $this->skill_prereq_satisfied;
        }
        
        private function skillCountSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $max_count_satisfied = true;
            if($skill_detail->getMaxCount() != -1) {
                
                $skill_count = 0;
                $player_character_skill_detail = $player_character_skill_set->getSkill($skill_detail->getSkillId());
                if (!empty($player_character_skill_detail)) {
                    $skill_count = $player_character_skill_detail->getPlayerCharacterSkillCount();
                }

                $max_count_satisfied = ($skill_count < $skill_detail->getMaxCount());
            }
            
            return $max_count_satisfied;
        }
        
        private function isSubset(array $subset, array $superset): bool {
            // array_diff returns elements in $subset not found in $superset
            return empty(array_diff($subset, $superset));
        }

        public function render(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $this->qualified = $this->isCharacterQualified($character_details, $player_character_skill_set);
            $skill_id = $this->getSkillId();
            $all_skill_instances = $player_character_skill_set->getAllSkillInstances($skill_id);
            $this->count_skill_instances = count($all_skill_instances);

            if (!$this->qualified && $this->count_skill_instances == 0) {
                return '';
            }
    
            $output_html = '<div class="skillContainer">' . PHP_EOL;
            if ($this->count_skill_instances > 0) {
                $output_html .= $this->renderExistingSkillInstances($character_details, $all_skill_instances);
            }

            if($this->qualified) {
                if ($this->count_skill_instances > 0) {
                    $output_html .= '<hr>';
                }

                $output_html .= $this->renderNewSkillInstance($character_details, $player_character_skill_set);
            }

            $output_html .= '</div>' . PHP_EOL;

            return $output_html;
        }

        private function renderExistingSkillInstances(\CharacterDetails $character_details, $all_skill_instances) {
            $output_html = '';
            if (!empty($all_skill_instances)) {
                $output_html .= '    <div>';
                foreach($all_skill_instances AS $skill_instance) {
                    $output_html .= $this->formatExistingSkillInstance($skill_instance);
                }
                $output_html .= '</div>' . PHP_EOL;
            }

            return $output_html;
        }

        private function formatExistingSkillInstance(\PlayerCharacterSkill $skill_instance) {
            $skill_name =  str_replace("'", "", html_entity_decode($skill_instance->getPlayerCharacterSkillName()));
            $form_id = $this->formIdLookup->getDeleteFormId();
            $talent_id = $this->formIdLookup->getDeleteWeaponTalentId();
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
            $delete_icon->addStyle("padding-right: 10px;");
            $delete_icon->addStyle("padding-left: 5px;");
            $delete_icon->setHoverText('Delete ' . $weapon_talent_desc);

            return $delete_icon->build();
        }

        private function renderNewSkillInstance(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $skill_name = $this->skill->getSkillName();
            $form_id = $this->formIdLookup->getAddFormId();
            $skill_catalog_element_id = $this->formIdLookup->getAddSkillCatalogElementId();
            $skill_catalog_value = $this->getSkillId();
            $weapon_proficiency_id = $this->formIdLookup->getAddWeaponProficiencyElementId();
            $weapon2_proficiency_id = $this->formIdLookup->getAddWeapon2ProficiencyElementId();

            $weapon_proficiency_value = $this->add_weapon_proficiency_value;
            $weapon2_proficiency_value = $this->add_weapon2_proficiency_value;

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
            $new_icon->addStyle("padding-right: 10px;");
            $new_icon->addStyle("padding-left: 5px;");

            return $new_icon->build();
        }

        public function dump() {
            $output  = PHP_EOL . '- ' . $this->skill->getSkillName() . ' -' . PHP_EOL;
            $output .= 'Qualified : ' . var_export($this->qualified, true) . PHP_EOL;
            $output .= 'Charisma satisfied : ' . var_export($this->charisma_satisfied, true) . PHP_EOL;
            $output .= 'Dexterity satisfied : ' . var_export($this->dexterity_satisfied, true) . PHP_EOL;
            $output .= 'Intelligence satisfied : ' . var_export($this->intelligence_satisfied, true) . PHP_EOL;
            $output .= 'Race satisfied : ' . var_export($this->race_satisfied, true) . PHP_EOL;
            $output .= 'Class/Level satisfied : ' . var_export($this->class_and_level_satisfied, true) . PHP_EOL;
            $output .= 'Skill count satisfied : ' . var_export($this->skill_count_satisfied, true) . PHP_EOL;
            $output .= 'Skill prereq satisfied : ' . var_export($this->skill_prereq_satisfied, true) . PHP_EOL;
            $output .= '    Skill Prereq IDs : ' . var_export($this->skill->getPrerequisiteSKillIds(), true) . PHP_EOL;
            $output .= '    Character Skills : ' . var_export($this->player_character_skill_set->getPlayerCharacterSkillIds(), true) . PHP_EOL;
            $output .= 'Skill instance count : ' . $this->count_skill_instances . PHP_EOL;

            return $output;
        }
    }
    ?>
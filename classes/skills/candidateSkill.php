<?php
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../fa/faNewIcon.php';
    require_once __DIR__ . '/../../fa/faDeleteIcon.php';
    require_once __DIR__ . '/formIdLookup.php';

    abstract class CandidateSkill {
        protected $skill;
        protected $formIdLookup;
        
        abstract protected function getSkillId();

        // Debug variables
        protected $charisma_satisfied;
        protected $dexterity_satisfied;
        protected $intelligence_satisfied;
        protected $race_satisfied;
        protected $class_and_level_satisfied;
        protected $skill_count_satisfied;
        protected $skill_prereq_satisfied;
        protected $qualified;
        protected $count_skill_instances = -1;

        private $log = [];

        private $player_character_skill_set;

        public function __construct(\SkillCatalog $skill_catalog, \FormIdLookup $formIdLookup) {
            $skill_id = $this->getSkillId();
            $this->skill = $skill_catalog->getSkill($skill_id);

            $this->formIdLookup = $formIdLookup;
        }
        
        protected function isCharacterQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
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

            $this->skill_prereq_satisfied = $this->isSubset($skill_detail->getPrerequisiteSkillIds(), $player_character_skill_set->getPlayerCharacterSkillIds());
            
            return $this->skill_count_satisfied && $this->skill_prereq_satisfied;
        }
        
        protected function skillCountSatisfied(\PlayerCharacterSkillSet $player_character_skill_set, \SkillDetail $skill_detail) {
            $max_count_satisfied = true;
            if($skill_detail->getMaxCount() != -1) {
                
                $skill_count = 0;
                $player_character_skill_detail = $player_character_skill_set->getAllSkillInstances($skill_detail->getSkillId());
                if (!empty($player_character_skill_detail)) {
                    $skill_count = count($player_character_skill_detail);
                }

                $max_count_satisfied = ($skill_count < $skill_detail->getMaxCount());
            }
            
            return $max_count_satisfied;
        }
        
        protected function isSubset(array $subset, array $superset): bool {
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

        protected function renderExistingSkillInstances(\CharacterDetails $character_details, $all_skill_instances) {
            $output_html = '';
            if (!empty($all_skill_instances)) {
                $output_html .= '    <div>' . PHP_EOL;
                foreach($all_skill_instances AS $skill_instance) {
                    $output_html .= $this->formatExistingSkillInstance($skill_instance, $character_details);
                }
                $output_html .= '    </div>' . PHP_EOL;
            }

            return $output_html;
        }

        protected function formatExistingSkillInstance(\PlayerCharacterSkill $skill_instance, \CharacterDetails $character_details) {
            $skill_name =  str_replace("'", "", html_entity_decode($skill_instance->getPlayerCharacterSkillName()));
            $form_id = $this->formIdLookup->getDeleteFormId();
            $talent_id = $this->formIdLookup->getDeleteSkillElementId();
            $player_character_talent_id = $skill_instance->getPlayerCharacterSkillId();

            $output_html  = '    <div style="padding-top: 5px;">';
            $output_html .= $this->buildDeletePlayerCharacterSkillIcon($form_id, $talent_id, $player_character_talent_id, $skill_name);
            $output_html .= $this->renderExistingSkillFields($skill_instance, $character_details);
            $output_html .= '</div>' . PHP_EOL;

            return $output_html;
        }

        protected function renderExistingSkillFields(\PlayerCharacterSkill $skill_instance, \CharacterDetails $character_details) {
            return $skill_instance->getPlayerCharacterSkillName();
        }

        protected function buildDeletePlayerCharacterSkillIcon($delete_form_id, $delete_skill_element_id, $delete_skill_element_value_id, $skill_desc) {
            $delete_icon = new FaDeleteIcon();
            $delete_icon->setOnClickJsFunction('confirmPlayerCharacterSkillDelete');
            $delete_icon->addOnclickJsParameter($delete_form_id);
            $delete_icon->addOnclickJsParameter($delete_skill_element_id);
            $delete_icon->addOnclickJsParameter($delete_skill_element_value_id);
            $delete_icon->addOnclickJsParameter($skill_desc);
            $delete_icon->addStyle("padding-right: 10px;");
            $delete_icon->addStyle("padding-left: 5px;");
            $delete_icon->setHoverText('Delete ' . $skill_desc);

            return $delete_icon->build();
        }

        protected function renderNewSkillInstance(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $skill_name = $this->skill->getSkillName();
            $form_id = $this->formIdLookup->getAddFormId();
            $skill_catalog_element_id = $this->formIdLookup->getAddSkillCatalogElementId();
            $skill_catalog_value = $this->getSkillId();

            $output_html = '';
            $output_html  = '    <div>';
            $output_html .= $this->buildAddPlayerCharacterSkillIcon($form_id, $skill_catalog_element_id, $skill_catalog_value);
            $output_html .= $this->renderNewSkillFields($skill_name, $character_details);
            $output_html .= '</div>' . PHP_EOL;
            return $output_html;
        }

        protected function renderNewSkillFields($skill_name, \CharacterDetails $character_details) {
            return $skill_name;
        }

        protected function buildAddPlayerCharacterSkillIcon($add_form_id, $skill_catalog_element_id, $skill_catalog_value) {
            $new_icon = new FaNewIcon();
            $new_icon->setOnClickJsFunction('submitAddSkillForm');
            $new_icon->addOnclickJsParameter($add_form_id);
            $new_icon->addOnclickJsParameter($skill_catalog_element_id);
            $new_icon->addOnclickJsParameter($skill_catalog_value);
            $new_icon->addStyle("padding-right: 10px;");
            $new_icon->addStyle("padding-left: 5px;");

            return $new_icon->build();
        }

        public function dump() {
            $bar  = '';
            for ($i = 0; $i < strlen($this->skill->getSkillName()) + 4; $i++) {
                $bar .= '-';
            }

            $output  = PHP_EOL . $bar . PHP_EOL;
            $output .= '- ' . $this->skill->getSkillName() . ' -' . PHP_EOL;
            $output .= $bar . PHP_EOL;
            $output .= 'Qualified : ' . var_export($this->qualified, true) . PHP_EOL;
            $output .= 'Charisma satisfied : ' . var_export($this->charisma_satisfied, true) . PHP_EOL;
            $output .= 'Dexterity satisfied : ' . var_export($this->dexterity_satisfied, true) . PHP_EOL;
            $output .= 'Intelligence satisfied : ' . var_export($this->intelligence_satisfied, true) . PHP_EOL;
            $output .= 'Race satisfied : ' . var_export($this->race_satisfied, true) . PHP_EOL;
            $output .= 'Class/Level satisfied : ' . var_export($this->class_and_level_satisfied, true) . PHP_EOL;
            $output .= 'Skill count satisfied : ' . var_export($this->skill_count_satisfied, true) . PHP_EOL;
            $output .= 'Skill prereq satisfied : ' . var_export($this->skill_prereq_satisfied, true) . PHP_EOL;
            $output .= '    Skill Prereq IDs : ' . var_export($this->skill->getPrerequisiteSkillIds(), true) . PHP_EOL;
            $output .= '    Character Skills : ' . var_export($this->player_character_skill_set->getPlayerCharacterSkillIds(), true) . PHP_EOL;
            $output .= 'Skill instance count : ' . $this->count_skill_instances . PHP_EOL;
            if (count($this->log) > 0) {
                $output .= '- Log [START] -' . PHP_EOL;
                foreach($this->log AS $log_entry) {
                    $output .= $log_entry . PHP_EOL;
                }
                $output .= '- Log [ END ] -' . PHP_EOL;
            }

            return $output;
        }
    }
    ?>
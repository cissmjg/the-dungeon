<?php
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../fa/faNewIcon.php';
    require_once __DIR__ . '/../../fa/faDeleteIcon.php';
    require_once __DIR__ . '/formIdLookup.php';
    require_once 'candidateSkill.php';

    abstract class CandidateWeaponSkill extends CandidateSkill {
        protected $weapon_proficiency_value;
        public function getWeaponProficiencyValue() {
            return $this->weapon_proficiency_value;
        }

        public function setWeaponProficiencyValue($weapon_proficiency_value) {
            $this->weapon_proficiency_value = $weapon_proficiency_value;
        }

        protected $weapon2_proficiency_value;
        public function getWeapon2ProficiencyValue() {
            return $this->weapon2_proficiency_value;
        }

        public function setWeapon2ProficiencyValue($weapon_proficiency_value) {
            $this->weapon2_proficiency_value = $weapon_proficiency_value;
        }
        
        abstract protected function getSkillId();

        public function render(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $this->qualified = $this->isCharacterQualified($character_details, $player_character_skill_set);
            $skill_id = $this->getSkillId();
            $all_skill_instances = $player_character_skill_set->getAllSkillInstancesForWeapon($skill_id, $this->getWeaponProficiencyValue());

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
                    if($skill_instance->getWeaponProficiencyId() == $this->getWeaponProficiencyValue()) {
                        $output_html .= $this->formatExistingSkillInstance($skill_instance, $character_details);
                    }
                }
                $output_html .= '    </div>' . PHP_EOL;
            }

            return $output_html;
        }

        protected function renderNewSkillInstance(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $skill_name = $this->skill->getSkillName();
            $form_id = $this->formIdLookup->getAddFormId();
            $skill_catalog_element_id = $this->formIdLookup->getAddSkillCatalogElementId();
            $skill_catalog_value = $this->getSkillId();
            $weapon2_element_id = $this->formIdLookup->getAddWeapon2ProficiencyElementId();

            $output_html = '';
            $output_html  = '    <div>';
            $output_html .= $this->buildAddPlayerCharacterWeaponTalentIcon($form_id, $skill_catalog_element_id, $skill_catalog_value, $weapon2_element_id);
            $output_html .= $this->renderNewSkillFields($skill_name, $character_details);
            $output_html .= '</div>' . PHP_EOL;
            return $output_html;
        }

        protected function renderNewSkillFields($skill_name, \CharacterDetails $character_details) {
            return $skill_name;
        }

        protected function buildAddPlayerCharacterWeaponTalentIcon($add_form_id, $skill_catalog_element_id, $skill_catalog_value, $weapon2_element_id) {
            $new_icon = new FaNewIcon();
            $new_icon->setOnClickJsFunction('submitAddWeaponTalentForm');
            $new_icon->addOnclickJsParameter($add_form_id);
            $new_icon->addOnclickJsParameter($skill_catalog_element_id);
            $new_icon->addOnclickJsParameter($skill_catalog_value);
            $new_icon->addOnclickJsParameter($weapon2_element_id);
            $new_icon->addStyle("padding-right: 10px;");
            $new_icon->addStyle("padding-left: 5px;");

            return $new_icon->build();
        }
    }
    ?>
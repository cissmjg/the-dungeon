<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'rmCategory.php';
    require_once 'rmCollectionCalculator.php';
    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';

    class MeleeDamageRmCollectionCalculator extends RmCollectionCalculator {

        protected $rm_weapon_collection;
        public function getWeaponCollection() {
            return $this->rm_weapon_collection;
        }
        public function aggregate() {
            $rmFactorResult = 0;
            foreach($this->rm_weapon_collection AS $rmFactor) {
                $rmFactorResult += $rmFactor->getRMData();
            }

            return $rmFactorResult;
        }

        public function __construct() {
            $this->rm_weapon_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

            // Attributes
            $rm_strength_bonus = new RmFactor("Strength", $attribute_metadata->getStrengthDamageAdjustment());
            $this->rm_weapon_collection->add($rm_strength_bonus);

            // Skills
            $rm_skill_collection = $this->getRmSkills($player_character_skill_set, $player_character_weapon);
            $this->rm_weapon_collection->addAll($rm_skill_collection);

            // Weapon
            $rm_weapon = $this->getWeaponBonus($player_character_weapon);
            if (!empty($rm_weapon)) {
                $this->rm_weapon_collection->add($rm_weapon);
            }
        }

        private function getRmSkills(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_collection = new RmCollection();

            // Fist of Iron
            $rm_fist_of_iron = $this->getRmFistOfIron($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_fist_of_iron)) {
                $rm_collection->add($rm_fist_of_iron);
            }

            // Weapon Focus Technique
            $rm_weapon_focus_technique = $this->getRmWeaponFocusTechnique($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_focus_technique)) {
                $rm_collection->add($rm_weapon_focus_technique);
            }

            // Weapon Focus (Greater) Technique
            $rm_weapon_focus_greater_technique = $this->getWeaponFocusGreaterTechnique($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_focus_greater_technique)) {
                $rm_collection->add($rm_weapon_focus_greater_technique);
            }

            // Specialization
            $rm_weapon_specialization = $this->getWeaponSpecialization($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_specialization)) {
                $rm_collection->add($rm_weapon_specialization);
            }

            // Double Specialization
            $rm_double_weapon_specialization = $this->getWeaponDoubleSpecialization($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_double_weapon_specialization)) {
                $rm_collection->add($rm_double_weapon_specialization);
            }

            return $rm_collection;
        }

        private function getRmFistOfIron(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_fist_of_iron = null;
            if ($player_character_weapon->getWeaponProficiencyId() == FIST) {
                $count_fist_of_iron = count($player_character_skill_set->getAllSkillInstances(FIST_OF_IRON));
                $has_fist_of_iron = $count_fist_of_iron > 0;
                if ($has_fist_of_iron) {
                    $rm_fist_of_iron_desc = sprintf("Fist of Iron +%dd4", $count_fist_of_iron);
                    $rm_fist_of_iron_modifier = 0;
                    $rm_fist_of_iron = new RmFactor($rm_fist_of_iron_desc, $rm_fist_of_iron_modifier);
                }
            }

            return $rm_fist_of_iron;
        }

        private function getRmWeaponFocusTechnique(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_focus_technique = null;
            $existing_weapon_focus_technique = $player_character_skill_set->getAllSkillInstances(WEAPON_FOCUS_TECHNIQUE);
            $has_weapon_focus_technique = count($existing_weapon_focus_technique) > 0;
            if ($has_weapon_focus_technique) {
                $first_weapon_focus_technique = $existing_weapon_focus_technique[0];
                if ($first_weapon_focus_technique->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_focus_technique_desc = "Weapon Focus Technique";
                    $rm_weapon_focus_technique_modifier = 1;
                    $rm_weapon_focus_technique = new RmFactor($rm_weapon_focus_technique_desc, $rm_weapon_focus_technique_modifier);
                }
            }
            return $rm_weapon_focus_technique;
        }

        private function getWeaponFocusGreaterTechnique($player_character_skill_set, $player_character_weapon) {
            $rm_weapon_focus_greater_technique = null;
            $existing_weapon_focus_greater_technique = $player_character_skill_set->getAllSkillInstances(WEAPON_FOCUS_GREATER_TECHNIQUE);
            $has_weapon_focus_greater_technique = count($existing_weapon_focus_greater_technique) > 0;
            if ($has_weapon_focus_greater_technique) {
                $first_weapon_focus_greater_technique = $existing_weapon_focus_greater_technique[0];
                if ($first_weapon_focus_greater_technique->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_focus_greater_technique_desc = "Weapon Focus (Greater) Technique";
                    $rm_weapon_focus_greater_technique_modifier = 1;
                    $rm_weapon_focus_greater_technique = new RmFactor($rm_weapon_focus_greater_technique_desc, $rm_weapon_focus_greater_technique_modifier);
                }
            }

            return $rm_weapon_focus_greater_technique;
        }

        private function getWeaponSpecialization(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_specialization = null;
            $existing_weapon_specialization = $player_character_skill_set->getAllSkillInstances(SPECIALIZATION);
            $has_weapon_specialization = count($existing_weapon_specialization) > 0;
            if ($has_weapon_specialization) {
                $first_weapon_specialization = $existing_weapon_specialization[0];
                if ($first_weapon_specialization->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_specialization_desc = "Specialization";
                    $rm_weapon_specialization_modifier = 2;
                    $rm_weapon_specialization = new RmFactor($rm_weapon_specialization_desc, $rm_weapon_specialization_modifier);
                }
            }

            return $rm_weapon_specialization;
        }

        private function getWeaponDoubleSpecialization(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_double_specialization = null;
            $exising_weapon_double_specialization = $player_character_skill_set->getAllSkillInstances(DOUBLE_SPECIALIZATION);
            $has_weapon_double_specialization = count($exising_weapon_double_specialization) > 0;
            if ($has_weapon_double_specialization) {
                $first_weapon_double_specialization = $exising_weapon_double_specialization[0];
                if ($first_weapon_double_specialization->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_double_specialization_desc = "Double Specialization";
                    $rm_weapon_double_specialization_modifier = 3;
                    $rm_weapon_double_specialization = new RmFactor($rm_weapon_double_specialization_desc, $rm_weapon_double_specialization_modifier);
                }
            }

            return $rm_weapon_double_specialization;
        }

        private function getWeaponBonus(PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon = null;
            if ($player_character_weapon->getMeleeDamageBonus() != 0) {
                if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MASTERCRAFT) {
                    $rm_weapon_desc = 'Mastercraft Bonus';
                } else if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MAGIC) {
                    $rm_weapon_desc = 'Magic Bonus';
                }
                $rm_weapon_modifier = $player_character_weapon->getMeleeDamageBonus();
                if ($rm_weapon_modifier < 0) {
                    $rm_weapon_modifier->setRmCategory(ROLL_MODIFIER_PENALTY);
                }

                $rm_weapon = new RmFactor($rm_weapon_desc, $rm_weapon_modifier);
            }
            return $rm_weapon;
        }
    }
?>

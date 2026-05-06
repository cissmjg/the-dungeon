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
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../../dbio/constants/characterRaces.php';
    require_once __DIR__ . '/../../webio/craftStatus.php';

    class MissileMediumRangeDamageRmCollectionCalculator extends RmCollectionCalculator {

        protected $rm_medium_collection;
        public function getRmCollection() {
            return $this->rm_medium_collection;
        }

        public function __construct() {
            $this->rm_medium_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

            // Proficiency check
            if ($player_character_weapon->getIsProficient()) {

                // Skills
                $rm_skill_collection = $this->getRmSkills($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $this->rm_medium_collection->addAll($rm_skill_collection);
                
            }

            // Weapon
            $rm_weapon = $this->getWeaponBonus($player_character_weapon);
            if (!empty($rm_weapon)) {
                $this->rm_medium_collection->add($rm_weapon);
            }
        }

        private function getRmSkills(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $rm_collection = new RmCollection();

            // Weapon Focus Technique
            $rm_weapon_focus_technique = $this->getRmWeaponFocusTechnique($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_focus_technique)) {
                $rm_collection->add($rm_weapon_focus_technique);
            }

            // Specialization
            $rm_weapon_specialization = $this->getWeaponSpecialization($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_specialization)) {
                $rm_collection->add($rm_weapon_specialization);
            }

            return $rm_collection;
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

        private function getWeaponSpecialization(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_specialization = null;
            $existing_weapon_specialization = $player_character_skill_set->getAllSkillInstances(SPECIALIZATION);
            $has_weapon_specialization = count($existing_weapon_specialization) > 0;
            if ($has_weapon_specialization) {
                $first_weapon_specialization = $existing_weapon_specialization[0];
                if ($first_weapon_specialization->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_specialization_desc = "Specialization";
                    $rm_weapon_specialization_modifier = $this->getWeaponSpecializationModifier($player_character_weapon);
                    $rm_weapon_specialization = new RmFactor($rm_weapon_specialization_desc, $rm_weapon_specialization_modifier);
                }
            }

            return $rm_weapon_specialization;
        }
        private function getWeaponSpecializationModifier(PlayerCharacterWeapon $player_character_weapon) {
            if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW) {
                return 0;
            } else if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_CROSSBOW) {
                return 0;
            } else {
                return 2;
            }
        }

        private function getWeaponBonus(PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon = null;
            $rm_weapon_desc = '';
            if ($player_character_weapon->getMissileDamageBonus() != 0) {
                if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MASTERCRAFT) {
                    $rm_weapon_desc = 'Mastercraft Bonus';
                } else if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MAGIC) {
                    $rm_weapon_desc = 'Magic Bonus';
                }
                
                $rm_weapon_modifier = $player_character_weapon->getMissileDamageBonus();
                $rm_weapon = new RmFactor($rm_weapon_desc, $rm_weapon_modifier);
                if ($rm_weapon_modifier < 0) {
                    $rm_weapon->setRmCategory(ROLL_MODIFIER_PENALTY);
                }
            }
            return $rm_weapon;
        }

        private function getHurledWeaponBonus(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $is_hurled_weapon = isWeaponHurled($player_character_weapon->getWeaponProficiencyId());
            $is_not_arcane_spellcaster = !$character_details->isArcaneSpellcaster();
            $is_proficient = $player_character_weapon->getIsProficient();

            // Note : if the character has Power Throw, strength as ALREADY been applied to Damage
            $does_not_have_power_throw = empty($player_character_skill_set->getAllSkillInstances(POWER_THROW));

            $rm_hurled_weapon = null;
            if ($is_hurled_weapon && $is_proficient && $is_not_arcane_spellcaster && $does_not_have_power_throw) {
                $rm_hurled_weapon_desc = "Hurled Strength";
                $rm_hurled_weapon_modifier = $attribute_metadata->getStrengthDamageAdjustment();

                $rm_hurled_weapon = new RmFactor($rm_hurled_weapon_desc, $rm_hurled_weapon_modifier);
            }

            return $rm_hurled_weapon;
        }

        private function getCompositeBowBonus(PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $rm_composite_bow = null;
            if ($player_character_weapon->getWeaponProficiencyId() == LONG_COMPOSITE_BOW || $player_character_weapon->getWeaponProficiencyId() == SHORT_COMPOSITE_BOW) {
                $rm_composite_bow_desc = "Strength Weapon";
                $rm_composite_bow_modifier = $attribute_metadata->getStrengthDamageAdjustment();
                $rm_composite_bow = new RmFactor($rm_composite_bow_desc, $rm_composite_bow_modifier);
            }

            return $rm_composite_bow;
        }
    }
?>

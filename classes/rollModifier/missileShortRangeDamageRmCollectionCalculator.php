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
    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../webio/craftStatus.php';

    class MissileShortRangeDamageRmCollectionCalculator extends RmCollectionCalculator {

        protected $rm_short_collection;
        public function getRmCollection() {
            return $this->rm_short_collection;
        }

        public function __construct() {
            $this->rm_short_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

            // Attributes
            $rm_attribute_bonus = $this->getAttributeModifier($player_character_skill_set, $attribute_metadata, $player_character_weapon);
            if (!empty($rm_attribute_bonus)) {
                $this->rm_short_collection->add($rm_attribute_bonus);
            }

            // Proficiency check
            if ($player_character_weapon->getIsProficient()) {

                // Skills
                $rm_skill_collection = $this->getRmSkills($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $this->rm_short_collection->addAll($rm_skill_collection);

                // Bow (Strength bonus for weapon)
                $rm_composite_bow_bonus = $this->getCompositeBowBonus($player_character_weapon, $attribute_metadata);
                if (!empty($rm_composite_bow_bonus)) {
                    $this->rm_short_collection->add($rm_composite_bow_bonus);
                }

                // Check for strength bonus on hurled weapons
                $rm_hurled_bonus = $this->getHurledWeaponBonus($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                if (!empty($rm_hurled_bonus)) {
                    $this->rm_short_collection->add($rm_hurled_bonus);
                }
            }

            // Weapon
            $rm_weapon = $this->getWeaponBonus($player_character_weapon);
            if (!empty($rm_weapon)) {
                $this->rm_short_collection->add($rm_weapon);
            }
        }

        private function getAttributeModifier(PlayerCharacterSkillSet $player_character_skill_set, AttributeMetadata $attribute_metadata, PlayerCharacterWeapon $player_character_weapon) {
            $has_power_throw = count($player_character_skill_set->getAllSkillInstances(POWER_THROW)) > 0;

            $rm_attribute_damage = null;
            $rm_attribute_desc = '';
            $rm_attribute_modifier = 0;

            // If the character has Power Throw, then apply strength damage bonus for hurled weapons
            if ($has_power_throw && isWeaponHurled($player_character_weapon->getWeaponProficiencyId())) {
                $rm_attribute_desc = 'Power Throw';
                $rm_attribute_modifier = $attribute_metadata->getStrengthDamageAdjustment();
                $rm_attribute_damage = new RmFactor($rm_attribute_desc, $rm_attribute_modifier);
            }

            return $rm_attribute_damage;
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
                return 1;
            } else if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_CROSSBOW) {
                return 1;
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
            $is_not_arcane_spellcaster = !$this->isOnlyArcaneSpellcaster($character_details);
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

        private function isOnlyArcaneSpellcaster(CharacterDetails $character_details) {
            $is_only_spell_caster = false;
            $class_count = $character_details->classCount();
            $primary_class_id = $character_details->getPrimaryClass();

            $is_only_spell_caster = ($class_count == 1 && ($primary_class_id == MAGIC_USER || $primary_class_id == ILLUSIONIST || $primary_class_id == WU_JEN || $primary_class_id == GREATER_MAGE));
            return $is_only_spell_caster;
        }
    }
?>

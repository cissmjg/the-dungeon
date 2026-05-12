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

    class MissileLongRangeToHitRmCollectionCalculator extends RmCollectionCalculator {

        private const LONG_RANGE_PENALTY = -5;

        protected $rm_long_collection;
        public function getRmCollection() {
            return $this->rm_long_collection;
        }

        public function __construct() {
            $this->rm_long_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            
            // Short range penalty
            $rm_long_range = $this->getRmLongRangePenalty();
            $this->rm_long_collection->add($rm_long_range);

            // Attributes
            $rm_attribute_bonus = $this->getAttributeModifier($player_character_skill_set, $attribute_metadata, $player_character_weapon);
            $this->rm_long_collection->add($rm_attribute_bonus);

            // Proficiency check
            if ($player_character_weapon->getIsProficient()) {

                // Skills
                $rm_skill_collection = $this->getRmSkills($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $this->rm_long_collection->addAll($rm_skill_collection);

            } else {
                $rm_non_proficient = $this->getNonProficiencyPenalty($character_details);
                $this->rm_long_collection->add($rm_non_proficient);
            }

            // Race
            $rm_race_bonus = $this->getRacialBonus($character_details, $player_character_weapon);
            if (!empty($rm_race_bonus)) {
                $this->rm_long_collection->add($rm_race_bonus);
            }

            // Weapon
            $rm_weapon = $this->getWeaponBonus($player_character_weapon);
            if (!empty($rm_weapon)) {
                $this->rm_long_collection->add($rm_weapon);
            }
        }

        private function getRmLongRangePenalty() {
            $rm_long_range_penalty_desc = "Long Range";
            $rm_long_range_penalty_modified = MissileLongRangeToHitRmCollectionCalculator::LONG_RANGE_PENALTY;
            $rm_long_range = new RmFactor($rm_long_range_penalty_desc, $rm_long_range_penalty_modified);
            $rm_long_range->setRmCategory(ROLL_MODIFIER_PENALTY);

            return $rm_long_range;
        }

        private function getAttributeModifier(PlayerCharacterSkillSet $player_character_skill_set, AttributeMetadata $attribute_metadata, PlayerCharacterWeapon $player_character_weapon) {
            $has_zen_archery = count($player_character_skill_set->getAllSkillInstances(ZEN_ARCHERY)) > 0;

            $rm_attribute_to_hit = null;
            $rm_attribute_desc = '';
            $rm_attribute_modifier = 0;

            // If the character has Zen Archery, then apply wisdom magical attack bonus
            if ($has_zen_archery) {
                $rm_attribute_desc = 'Zen Archery';
                $rm_attribute_modifier = $attribute_metadata->getMagicalAttackAdjustment();
            // Use dexterity Reaction/Missile adjustment
            } else {
                $rm_attribute_desc = 'Dexterity';
                $rm_attribute_modifier = $attribute_metadata->getReactionMissileAdjustment();
            }

            $rm_attribute_to_hit = new RmFactor($rm_attribute_desc, $rm_attribute_modifier);
            return $rm_attribute_to_hit;
        }

        private function getRmSkills(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $rm_collection = new RmCollection();

            // Cleric's Preferred Weapon
            $rm_clerics_preferred_weapon = $this->getRmClericsPreferredWeapon($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_clerics_preferred_weapon)) {
                $rm_collection->add($rm_clerics_preferred_weapon);
            }

            // Weapon Focus Accuracy
            $rm_weapon_focus_accuracy = $this->getRmWeaponFocusAccuracy($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_focus_accuracy)) {
                $rm_collection->add($rm_weapon_focus_accuracy);
            }

            // Specialization
            $rm_weapon_specialization = $this->getWeaponSpecialization($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_specialization)) {
                $rm_collection->add($rm_weapon_specialization);
            }

            return $rm_collection;
        }

        private function getRmClericsPreferredWeapon(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_clerics_preferred_weapon = null;
            $existing_clerics_preferred_weapon = $player_character_skill_set->getAllSkillInstances(CLERICS_PREFERRED_WEAPON);
            $count_clerics_preferred_weapon = count($existing_clerics_preferred_weapon);
            $has_clerics_preferred_weapon = $count_clerics_preferred_weapon > 0;
            if ($has_clerics_preferred_weapon) {
                $first_clerics_preferred_weapon = $existing_clerics_preferred_weapon[0];
                if ($first_clerics_preferred_weapon->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                     $rm_clerics_preferred_weapon_desc = sprintf("Cleric's preferred Weapon (%d)", $count_clerics_preferred_weapon);
                     $rm_clerics_preferred_weapon_modifier = $count_clerics_preferred_weapon - 1;
                     $rm_clerics_preferred_weapon = new RmFactor($rm_clerics_preferred_weapon_desc, $rm_clerics_preferred_weapon_modifier);
                }
            }

            return $rm_clerics_preferred_weapon;
        }

        private function getRmWeaponFocusAccuracy(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_focus_accuracy = null;
            $existing_weapon_focus_accuracy = $player_character_skill_set->getAllSkillInstances(WEAPON_FOCUS_ACCURACY);
            $has_weapon_focus_accuracy = count($existing_weapon_focus_accuracy) > 0;
            if ($has_weapon_focus_accuracy) {
                $first_weapon_focus_accuracy = $existing_weapon_focus_accuracy[0];
                if ($first_weapon_focus_accuracy->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_focus_accuracy_desc = "Weapon Focus Accuracy";
                    $rm_weapon_focus_accuracy_modifier = 1;
                    $rm_weapon_focus_accuracy = new RmFactor($rm_weapon_focus_accuracy_desc, $rm_weapon_focus_accuracy_modifier);
                }
            }
            return $rm_weapon_focus_accuracy;
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
                return 1;
            }
        }

        private function getRacialBonus(CharacterDetails $character_details, PlayerCharacterWeapon $player_character_weapon) {
            $rm_racial = null;
            if (
                $character_details->getRaceId() == RACE_ELF        || $character_details->getRaceId() == RACE_DARK_ELF ||
                $character_details->getRaceId() == RACE_GRAY_ELF   || $character_details->getRaceId() == RACE_HIGH_ELF ||
                $character_details->getRaceId() == RACE_VALLEY_ELF || $character_details->getRaceId() == RACE_WILD_ELF ||
                $character_details->getRaceId() == RACE_WOOD_ELF
               ) {
                if ($player_character_weapon->getMissileWeaponSubtype()== WEAPON_SUBTYPE_BOW) {
                        $rm_racial_desc = "Elven racial bonus";
                        $rm_racial_modifier = 1;
                        $rm_racial = new RmFactor($rm_racial_desc, $rm_racial_modifier);
                }
            }

            return $rm_racial;
        }

        private function getWeaponBonus(PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon = null;
            $rm_weapon_desc = '';
            if ($player_character_weapon->getMissileHitBonus() != 0) {
                if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MASTERCRAFT) {
                    $rm_weapon_desc = 'Mastercraft Bonus';
                } else if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MAGIC) {
                    $rm_weapon_desc = 'Magic Bonus';
                }
                
                $rm_weapon_modifier = $player_character_weapon->getMissileHitBonus();
                $rm_weapon = new RmFactor($rm_weapon_desc, $rm_weapon_modifier);
                if ($rm_weapon_modifier < 0) {
                    $rm_weapon->setRmCategory(ROLL_MODIFIER_PENALTY);
                }
            }
            return $rm_weapon;
        }

        private function getNonProficiencyPenalty(CharacterDetails $character_details) {
            $rm_non_proficiency_desc = "Non Proficiency Penalty";
            $rm_non_proficient = new RmFactor($rm_non_proficiency_desc, $character_details->getNonProficienyPenalty());
            $rm_non_proficient->setRmCategory(ROLL_MODIFIER_PENALTY);

            return $rm_non_proficient;
        }
    }
?>

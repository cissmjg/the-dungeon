<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'rmCategory.php';
    require_once 'clericsPreferredWeapon.php';
    require_once 'formIdLookup.php';
    require_once 'skillCatalog.php';

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/characterRaces.php';

    class meleeToHitRmCollectionCalculator extends RmCollectionCalculator {

        protected $rm_weapon_collection;
        public function getWeaponCollection() {
            return $this->rm_weapon_collection;
        }

        public function __construct() {
            $this->rm_weapon_collection = new RmCollection();
        }

        protected function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

            // Attributes
            $rm_strength_bonus = $this->getRmStrengthBonus($player_character_skill_set, $attribute_metadata, $player_character_weapon);
            $this->rm_weapon_collection->add($rm_strength_bonus);

            // Proficiency check
            if (PlayerCharacterWeapon::isSkillProficient($player_character_weapon->getWeaponProficiencyId(), $player_character_skill_set)) {

                // Skills
                $rm_skill_collection = $this->getRmSkills($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $this->rm_weapon_collection->addAll($rm_skill_collection);

            } else {
                 // Check for non-proficiency
                 if (!$player_character_weapon->getIsCombatProficient()) {
                    $rm_non_proficient = new RmFactor("Non Proficiency Penalty", $character_details->getNonProficienyPenalty());
                    $rm_non_proficient->setRmCategory(ROLL_MODIFIER_PENALTY);
                    $this->rm_weapon_collection->add($rm_non_proficient);
                 }
            }

            // Race
            $rm_race_bonus = $this->getRacialBonus($character_details, $player_character_weapon);
            if (!empty($rm_race_bonus)) {
                $this->rm_weapon_collection->add($rm_race_bonus);
            }

            // Weapon
            $rm_weapon = $this->getWeaponBonus($player_character_weapon);
            if (!empty($rm_weapon)) {
                $this->rm_weapon_collection->add($rm_weapon);
            }
        }

        private function getRmStrengthBonus(PlayerCharacterSkillSet $player_character_skill_set, AttributeMetadata $attribute_metadata, PlayerCharacterWeapon $player_character_weapon) {
            $has_weapon_finesse = count($player_character_skill_set->getAllSkillInstances(WEAPON_FINESSE)) > 0;
            $rm_strength_to_hit = null;

            // If the character has 'Weapon Finesse' and it is a melee weapon and the weapon is 1 handed, then apply dexterity reaction/missile bonus
            if ($has_weapon_finesse && $player_character_weapon->getMeleeWeaponType() = WEAPON_TYPE_MELEE && $player_character_weapon->getMeleeNumberOfHands() == 1) {
                $rm_strength_to_hit = new RmFactor("Weapon Finesse", $attribute_metadata->getReactionMissileAdjustment());
            } else if ($has_weapon_finesse && $player_character_weapon->getWeaponProficiencyId() == ELVEN_COURT_BLADE) {
                $rm_strength_to_hit = new RmFactor("Weapon Finesse", $attribute_metadata->getReactionMissileAdjustment());
            } else {
                $rm_strength_to_hit = new RmFactor("Strength", $attribute_metadata->getStrengthHitAdjustment());
            }

            return $rm_strength_to_hit;
        }

        private function getRmSkills(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $rm_collection = new RmCollection();

            // Dirty Fighting
            $rm_dirty_fighting = $this->getRmDirtyFighting($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_dirty_fighting)) {
                $rm_collection->add($rm_dirty_fighting);
            }

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

            // Weapon Focus (Greater) Accuracy
            $rm_weapon_focus_greater_accuracy = $this->getWeaponFocusGreaterAccuracy($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_focus_greater_accuracy)) {
                $rm_collection->add($rm_weapon_focus_greater_accuracy);
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

        private function getRmDirtyFighting(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_dirty_fighting = null;
            if ($player_character_weapon->getWeaponProficiencyId() == FIST) {
                $count_dirty_fighting = count($player_character_skill_set->getAllSkillInstances(DIRTY_FIGHTING));
                $has_dirty_fighting = $count_dirty_fighting > 0;
                if ($has_dirty_fighting) {
                    $rm_dirty_fighting_desc = sprintf("Dirty Fighting (%d)", $count_dirty_fighting);
                    $rm_dirty_fighting_modifier = $count_dirty_fighting * 2;
                    $rm_dirty_fighting = new RmFactor($rm_dirty_fighting_desc, $rm_dirty_fighting_modifier);
                }
            }

            return $rm_dirty_fighting;
        }

        private function getRmClericsPreferredWeapon(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_clerics_preferred_weapon = null;
            $existing_clerics_preferred_weapon = $player_character_skill_set->getAllSkillInstances(CLERICS_PREFERRED_WEAPON);
            $count_clerics_preferred_weapon = count($existing_clerics_preferred_weapon);
            $has_clerics_preferred_weapon = $count_clerics_preferred_weapon > 0;
            if ($has_clerics_preferred_weapon) {
                $first_clerics_preferred_weapon = $existing_clerics_preferred_weapon[0];
                $is_equivalent = isWeaponEquivalent($first_clerics_preferred_weapon->getWeaponProficiencyId(), $player_character_weapon->getWeaponProficiencyId());
                if ($is_equivalent) {
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
                $is_equivalent = isWeaponEquivalent($first_weapon_focus_accuracy->getWeaponProficiencyId(), $player_character_weapon->getWeaponProficiencyId());
                if ($is_equivalent) {
                    $rm_weapon_focus_accuracy_desc = "Weapon Focus Accuracy";
                    $rm_weapon_focus_accuracy_modifier = 1;
                    $rm_weapon_focus_accuracy = new RmFactor($rm_weapon_focus_accuracy_desc, $rm_weapon_focus_accuracy_modifier);
                }
            }
            return $rm_weapon_focus_accuracy;
        }

        private function getWeaponFocusGreaterAccuracy($player_character_skill_set, $player_character_weapon) {
            $rm_weapon_focus_greater_accuracy = null;
            $existing_weapon_focus_greater_accuracy = $player_character_skill_set->getAllSkillInstances(WEAPON_FOCUS_GREATER_ACCURACY);
            $has_weapon_focus_greater_accuracy = count($existing_weapon_focus_greater_accuracy) > 0;
            if ($has_weapon_focus_greater_accuracy) {
                $first_weapon_focus_greater_accuracy = $existing_weapon_focus_greater_accuracy[0];
                $is_equivalent = isWeaponEquivalent($first_weapon_focus_greater_accuracy->getWeaponProficiencyId(), $player_character_weapon->getWeaponProficiencyId());
                if ($is_equivalent) {
                    $rm_weapon_focus_greater_accuracy_desc = "Weapon Focus (Greater) Accuracy";
                    $rm_weapon_focus_greater_accuracy_modifier = 1;
                    $rm_weapon_focus_greater_accuracy = new RmFactor($rm_weapon_focus_greater_accuracy_desc, $rm_weapon_focus_greater_accuracy_modifier);
                }
            }

            return $rm_weapon_focus_greater_accuracy;
        }

        private function getWeaponSpecialization(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_specialization = null;
            $existing_weapon_specialization = $player_character_skill_set->getAllSkillInstances(SPECIALIZATION);
            $has_weapon_specialization = count($existing_weapon_specialization) > 0;
            if ($has_weapon_specialization) {
                $first_weapon_specialization = $existing_weapon_specialization[0];
                $is_equivalent = isWeaponEquivalent($first_weapon_specialization->getWeaponProficiencyId(), $player_character_weapon->getWeaponProficiencyId());
                if ($is_equivalent) {
                    $rm_weapon_specialization_desc = "Specialization";
                    $rm_weapon_specialization_modifier = 1;
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
                $is_equivalent = isWeaponEquivalent($first_weapon_double_specialization->getWeaponProficiencyId(), $player_character_weapon->getWeaponProficiencyId());
                if ($is_equivalent) {
                    $rm_weapon_double_specialization_desc = "Double Specialization";
                    $rm_weapon_double_specialization_modifier = 3;
                    $rm_weapon_double_specialization = new RmFactor($rm_weapon_double_specialization_desc, $rm_weapon_double_specialization_modifier);
                }
            }

            return $rm_weapon_double_specialization;
        }

        private function getRacialBonus(CharacterDetails $character_details, PlayerCharacterWeapon $player_character_weapon) {
            $rm_racial = null;
            if (
                $character_details->getRaceId() == RACE_ELF        || $character_details->getRaceId() == RACE_DARK_ELF ||
                $character_details->getRaceId() == RACE_GRAY_ELF   || $character_details->getRaceId() == RACE_HIGH_ELF ||
                $character_details->getRaceId() == RACE_VALLEY_ELF || $character_details->getRaceId() == RACE_WILD_ELF ||
                $character_details->getRaceId() == RACE_WOOD_ELF
               ) {
                if (
                    isWeaponEquivalent($player_character_weapon->getWeaponProficiencyId(), LONG_SWORD) ||
                    isWeaponEquivalent($player_character_weapon->getWeaponProficiencyId(), SHORT_SWORD)
                   ) {
                        $rm_racial_desc = "Elven racial bonus";
                        $rm_racial_modifier = 1;
                        $rm_racial = new RmFactor($rm_racial_desc, $rm_racial_modifier);
                }
            }

            return $rm_racial;
        }

        private function getWeaponBonus(PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon = null;
            if ($player_character_weapon->getMeleeHitBonus() != 0) {
                $rm_weapon_desc = "Magic Bonus";
                $rm_weapon_modifier = $player_character_weapon->getMeleeHitBonus();
                if ($rm_weapon_modifier < 0) {
                    $rm_weapon_modifier->setRmCategory(ROLL_MODIFIER_PENALTY);
                }
                $rm_weapon = new RmFactor($rm_weapon_desc, $rm_weapon_modifier);
            }
            return $rm_weapon;
        }
    }
?>

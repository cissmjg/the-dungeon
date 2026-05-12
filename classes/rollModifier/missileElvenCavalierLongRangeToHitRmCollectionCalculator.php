<?php
    require_once 'elvenCavalierPreferredWeaponHelper.php';
    require_once 'missileLongRangeToHitRmCollectionCalculator.php';

    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';

    class MissileElvenCavalierLongRangeToHitRmCollectionCalculator extends MissileLongRangeToHitRmCollectionCalculator {
        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
            
            // Check to see if a 4th level preferred weapon is present
            $rm_4th_level_preferred_weapon = ElvenCavalierPreferredWeaponHelper::get4thLevelPreferredWeaponRm($character_details, $player_character_skill_set, $player_character_weapon);
            if (!empty($rm_4th_level_preferred_weapon)) {
                $this->rm_long_collection->add($rm_4th_level_preferred_weapon);
            }

            // Check to see if a 6th level preferred weapon is present
            $rm_6th_level_preferred_weapon = ElvenCavalierPreferredWeaponHelper::get6thLevelPreferredWeaponRm($character_details, $player_character_skill_set, $player_character_weapon);
            if (!empty($rm_6th_level_preferred_weapon)) {
                $this->rm_long_collection->add($rm_6th_level_preferred_weapon);
            }

            $rm_short_bow = $this->getElvenCavalierShortBowBonus($character_details, $player_character_skill_set, $player_character_weapon);
            if (!empty($rm_short_bow)) {
                $this->rm_long_collection->add($rm_short_bow);
            }
        }

        private function getElvenCavalierShortBowBonus(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_elven_cavalier_short_bow_bonus = null;

            // Ensure the character is an Elven Cavalier
            if ($character_details->containsClassId(ELVEN_CAVALIER)) {
                // Ensure the weapon is a short bow
                if ($player_character_weapon->getWeaponProficiencyId() == SHORT_BOW || $player_character_weapon->getWeaponProficiencyId() == SHORT_COMPOSITE_BOW) {
                    $rm_elven_cavalier_short_bow_bonus_desc = 'Cavalier Bonus';
                    $cavalier_level = $character_details->getPrimaryClass()->getClassLevel();
                    $rm_elven_cavalier_short_bow_bonus_modifier = $this->getElvenCavalierShortBowModifier($cavalier_level);
                    $rm_elven_cavalier_short_bow_bonus = new RmFactor($rm_elven_cavalier_short_bow_bonus_desc, $rm_elven_cavalier_short_bow_bonus_modifier);
                }
            }

            return $rm_elven_cavalier_short_bow_bonus;
        }

        private function getElvenCavalierShortBowModifier($cavalier_level) {
            if ($cavalier_level >= 0 && $cavalier_level < 6) {
                return 1;
            }

            if ($cavalier_level >= 6 && $cavalier_level < 12) {
                return 2;
            }

            if ($cavalier_level >= 12) {
                return 3;
            }
        }
    }
?>
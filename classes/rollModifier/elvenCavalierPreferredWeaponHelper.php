<?php
    require_once 'rmFactor.php';
    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';

    class ElvenCavalierPreferredWeaponHelper {
        public static function get4thLevelPreferredWeaponRm(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $primary_class = $character_details->getPrimaryClass();
            $character_level = $primary_class->getClassLevel();

            // Check to see if a 4th level preferred weapon is present
            $rm_4thLevel_preferred_weapon = null;
            $fourth_level_preferred_weapon = ElvenCavalierPreferredWeaponHelper::get4thlevelPreferredWeapon($player_character_skill_set);
            if (!empty($fourth_level_preferred_weapon)) {
                if ($fourth_level_preferred_weapon->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_4thLevel_preferred_weapon = ElvenCavalierPreferredWeaponHelper::calculate4thLevelPreferredWeaponBonus($character_level);
                }
            }

            return $rm_4thLevel_preferred_weapon;
        }
        
        private static function get4thlevelPreferredWeapon(PlayerCharacterSkillSet $player_character_skill_set) {
            $fourth_level_preferred_weapon = null;
            $weapon_proficiencies = $player_character_skill_set->getAllSkillInstances(WEAPON_PROFICIENCY);
            foreach($weapon_proficiencies AS $weapon_proficiency) {
                if ($weapon_proficiency->getIsPreferredElvenCavalierLevel4()) {
                    $fourth_level_preferred_weapon = $weapon_proficiency;
                    break;
                }
            }

            return $fourth_level_preferred_weapon;
        }

        private static function calculate4thLevelPreferredWeaponBonus($character_level) {
            if ($character_level >= 4 && $character_level < 8) {
                return new RmFactor("4th Level Preferred", 1);
            }

            if ($character_level >= 8) {
                return new RmFactor("4th Level Preferred", 2);
            }

            return null;
        }

        public static function get6thLevelPreferredWeaponRm(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $primary_class = $character_details->getPrimaryClass();
            $character_level = $primary_class->getClassLevel();

            // Check to see if a 6th level preferred weapon is present
            $rm_6thLevel_preferred_weapon = null;
            $sixth_level_preferred_weapon = ElvenCavalierPreferredWeaponHelper::get6thlevelPreferredWeapon($player_character_skill_set);
            if (!empty($sixth_level_preferred_weapon)) {
                if ($sixth_level_preferred_weapon->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_6thLevel_preferred_weapon = ElvenCavalierPreferredWeaponHelper::calculate6thLevelPreferredWeaponBonus($character_level);
                }
            }

            return $rm_6thLevel_preferred_weapon;
        }
        
        private static function get6thlevelPreferredWeapon(PlayerCharacterSkillSet $player_character_skill_set) {
            $sixth_level_preferred_weapon = null;
            $weapon_proficiencies = $player_character_skill_set->getAllSkillInstances(WEAPON_PROFICIENCY);
            foreach($weapon_proficiencies AS $weapon_proficiency) {
                if ($weapon_proficiency->getIsPreferredElvenCavalierLevel6()) {
                    $sixth_level_preferred_weapon = $weapon_proficiency;
                    break;
                }
            }

            return $sixth_level_preferred_weapon;
        }

        private static function calculate6thLevelPreferredWeaponBonus($character_level) {
            if ($character_level >= 6 && $character_level < 10) {
                return new RmFactor("6th Level Preferred", 1);
            }

            if ($character_level >= 10) {
                return new RmFactor("6th Level Preferred", 2);
            }

            return null;
        }

    }
?>
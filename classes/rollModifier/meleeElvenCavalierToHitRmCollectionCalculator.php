<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/skills.php';

class MeleeElvenCavalierToHitRmCollectionCalculator  extends meleeToHitRmCollectionCalculator {
    public function aggregate() {
        $rmFactorResult = 0;
        foreach($this->rm_weapon_collection AS $rmFactor) {
            $rmFactorResult += $rmFactor->getRMData();
        }

        return $rmFactorResult;
    }

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
        $this->calculateWeaponSpecificBonus($character_details, $player_character_skill_set, $player_character_weapon);
    }

    private function calculateWeaponSpecificBonus(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
        $primary_class = $character_details->getPrimaryClass();
        $character_level = $primary_class->getClassLevel();

        // Check for elven blade equivalents for Long Sword and Short Sword
        $is_long_sword = LONG_SWORD == $player_character_weapon->getWeaponProficiencyId();
        $is_short_sword = SHORT_SWORD == $player_character_weapon->getWeaponProficiencyId();

        if ($is_long_sword) {
            $this->calculateLongSwordBonus($character_level);
            return;
        }

        // Check for a 4th level preferred weapon. If not then processing is complete
        $fourth_level_preferred_weapon = $this->get4thlevelPreferredWeapon($player_character_skill_set);
        if (empty($fourth_level_preferred_weapon)) {
            return;
        }

        // Check to see if the 4th level preferred weapon is a short sword
        if ($fourth_level_preferred_weapon->getWeaponProficiencyId() == SHORT_SWORD && $is_short_sword) {
            $this->calculate4thLevelPreferredWeaponBonus($character_level);
        } else if ($fourth_level_preferred_weapon->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
            $this->calculate4thLevelPreferredWeaponBonus($character_level);
        }

        // Check for a 6th level preferred weapon.
        $sixth_level_preferred_weapon = $this->get6thlevelPreferredWeapon($player_character_skill_set);
        if (empty($sixth_level_preferred_weapon)) {
            return;
        }

        // Check to see if the 6th level preferred weapon is a short sword
        if ($sixth_level_preferred_weapon->getWeaponProficiencyId() == SHORT_SWORD && $is_short_sword) {
            $this->calculate6thLevelPreferredWeaponBonus($character_level);
        } else if ($sixth_level_preferred_weapon->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
            $this->calculate6thLevelPreferredWeaponBonus($character_level);
        }
    }

    private function calculateLongSwordBonus($character_level) {
            if ($character_level >= 2 && $character_level < 6) {
                $rm_factor = new RmFactor("Cavalier Level", 1);
                $this->rm_weapon_collection->add($rm_factor);
            }

            if ($character_level >= 6 && $character_level < 12) {
                $rm_factor = new RmFactor("Cavalier Level", 2);
                $this->rm_weapon_collection->add($rm_factor);
            }

            if ($character_level >= 12) {
                $rm_factor = new RmFactor("Cavalier Level", 3);
                $this->rm_weapon_collection->add($rm_factor);
            }
    }

    private function get4thlevelPreferredWeapon(PlayerCharacterSkillSet $player_character_skill_set) {
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

    private function calculate4thLevelPreferredWeaponBonus($character_level) {
        if ($character_level >= 4 && $character_level < 8) {
            $rm_factor = new RmFactor("4th Level Preferred", 1);
            $this->rm_weapon_collection->add($rm_factor);
        }

        if ($character_level >= 8) {
            $rm_factor = new RmFactor("4th Level Preferred", 2);
            $this->rm_weapon_collection->add($rm_factor);
        }
    }

    private function get6thlevelPreferredWeapon(PlayerCharacterSkillSet $player_character_skill_set) {
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

    private function calculate6thLevelPreferredWeaponBonus($character_level) {
        if ($character_level >= 6 && $character_level < 10) {
            $rm_factor = new RmFactor("6th Level Preferred", 1);
            $this->rm_weapon_collection->add($rm_factor);
        }

        if ($character_level >= 10) {
            $rm_factor = new RmFactor("6th Level Preferred", 2);
            $this->rm_weapon_collection->add($rm_factor);
        }
    }
}


?>

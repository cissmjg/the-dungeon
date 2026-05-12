<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'elvenCavalierPreferredWeaponHelper.php';

    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/skills.php';

class MeleeElvenCavalierToHitRmCollectionCalculator  extends meleeToHitRmCollectionCalculator {

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
        $this->calculateWeaponSpecificBonus($character_details, $player_character_skill_set, $player_character_weapon);
    }

    private function calculateWeaponSpecificBonus(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
        $primary_class = $character_details->getPrimaryClass();
        $character_level = $primary_class->getClassLevel();

        if (LONG_SWORD == $player_character_weapon->getWeaponProficiencyId()) {
            $this->calculateLongSwordBonus($character_level);
            return;
        }

        // Check to see if a 4th level preferred weapon is present
        $rm_4th_level_preferred_weapon = ElvenCavalierPreferredWeaponHelper::get4thLevelPreferredWeaponRm($character_details, $player_character_skill_set, $player_character_weapon);
        if (!empty($rm_4th_level_preferred_weapon)) {
            $this->rm_melee_to_hit_collection->add($rm_4th_level_preferred_weapon);
        }

        // Check to see if a 6th level preferred weapon is present
        $rm_6th_level_preferred_weapon = ElvenCavalierPreferredWeaponHelper::get6thLevelPreferredWeaponRm($character_details, $player_character_skill_set, $player_character_weapon);
        if (!empty($rm_6th_level_preferred_weapon)) {
            $this->rm_melee_to_hit_collection->add($rm_6th_level_preferred_weapon);
        }
    }

    private function calculateLongSwordBonus($character_level) {
        if ($character_level >= 2 && $character_level < 6) {
            $rm_factor = new RmFactor("Cavalier Level", 1);
            $this->rm_melee_to_hit_collection->add($rm_factor);
        }

        if ($character_level >= 6 && $character_level < 12) {
            $rm_factor = new RmFactor("Cavalier Level", 2);
            $this->rm_melee_to_hit_collection->add($rm_factor);
        }

        if ($character_level >= 12) {
            $rm_factor = new RmFactor("Cavalier Level", 3);
            $this->rm_melee_to_hit_collection->add($rm_factor);
        }
    }
}


?>

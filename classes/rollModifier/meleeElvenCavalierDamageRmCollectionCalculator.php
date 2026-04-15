<?php

require_once 'rmFactor';
require_once 'rmCollection.php';
require_once __DIR__ . '/../../dbio/constants/cavalierCombatMode.php';
require_once __DIR__ . '/../../dbio/constants/weapons.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class meleeElvenCavalierDamageRmCollectionCalculator  extends meleeDamageRmCollectionCalculator {

    private $combat_mode;
    public function getCombatMode() {
        return $this->combat_mode;
    }

    public function setCombatMode($combat_mode) {
        $this->combat_mode = $combat_mode;
    }

    protected function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);

        $is_long_sword = isWeaponEquivalent(LONG_SWORD, $player_character_weapon->getWeaponProficiencyId());
        if (!$is_long_sword) {
            return;
        }

        $rm_cavalier_damage_desc = "Cavalier Bonus";
        $rm_cavalier_damage_modifier = 0;
        if ($this->combat_mode == UNMOUNTED) {
            $rm_cavalier_damage_modifier = 1;
        } else {
            $primary_class = $character_details->getPrimaryClass();
            $rm_cavalier_damage_modifier = $primary_class->getClassLevel();
        }

        $rm_cavalier_damage = new RmFactor($rm_cavalier_damage_desc, $rm_cavalier_damage_modifier);
        $this->rm_weapon_collection->add($rm_cavalier_damage);
    }
}


?>

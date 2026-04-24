<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once __DIR__ . '/../../dbio/constants/cavalierCombatMode.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/skills.php';

class MeleeElvenCavalierDamageRmCollectionCalculator  extends meleeDamageRmCollectionCalculator {
    public function aggregate() {
        $rmFactorResult = 0;
        foreach($this->rm_weapon_collection AS $rmFactor) {
            $rmFactorResult += $rmFactor->getRMData();
        }

        return $rmFactorResult;
    }

    private $combat_mode;
    public function getCombatMode() {
        return $this->combat_mode;
    }

    public function setCombatMode($combat_mode) {
        $this->combat_mode = $combat_mode;
    }

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);

        if (LONG_SWORD != $player_character_weapon->getWeaponProficiencyId()){
            return;
        }

        $rm_cavalier_damage_desc = "Cavalier Level Bonus";
        $rm_cavalier_damage_modifier = 0;
        if ($this->combat_mode == COMBAT_MODE_UNMOUNTED) {
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

<?php
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

require_once __DIR__ . '/playerCharacterMeleeWeaponRenderer.php';
require_once __DIR__ . '/rollModifier/meleeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/meleeDamageRmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';
require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';

class PlayerCharacterMeleeElvenCavalierWeaponRenderer extends PlayerCharacterMeleeWeaponRenderer {

    public function render() {
        if (!$this->player_character_weapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
            return '';
        }

        $melee_to_hit_calculator = new MeleeElvenCavalierToHitRmCollectionCalculator();
        $melee_to_hit_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $melee_rm_dmg_calculator = new MeleeElvenCavalierDamageRmCollectionCalculator();
        $melee_rm_dmg_calculator->setCombatMode($this->getCombatMode());
        $melee_rm_dmg_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $attacks_per_round = $this->calculateAttacksPerRound($this->player_character_skill_set, $this->player_character_weapon, $this->character_details);

        $weapon_panel_section_name = $this->combat_mode == COMBAT_MODE_MOUNTED ? "mounted-" : "unmounted-";
        $weapon_panel_name = 'weapon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
		$weapon_panel_icon_name = 'weapon-icon-' . $weapon_panel_section_name . $this->player_character_weapon->getWeaponId();
        
        $weapon_panel  = $this->buildWeaponDetailEntry($this->player_character_weapon, $melee_to_hit_calculator, $melee_rm_dmg_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        $weapon_panel .= $this->buildRmWeaponPanel($melee_to_hit_calculator, $melee_rm_dmg_calculator, $weapon_panel_name);

        return $weapon_panel;
    }

    public function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details) {
        $attacks_per_round = ATTACKS_PER_ROUND_1_FOR_1;
        $primary_class = $character_details->getPrimaryClass();

        $is_preferred = $player_character_weapon->getWeaponProficiencyId() == LONG_SWORD || $player_character_skill_set->isWeaponPreferred($player_character_weapon->getWeaponProficiencyId());
        $attacks_per_round = getAttacksPerRound($primary_class->getClassId(), $primary_class->getClassLevel(), $is_preferred, $this->combat_mode == COMBAT_MODE_MOUNTED, $player_character_weapon->getWeaponProficiencyId());

        return getAttacksPerRoundDescription($attacks_per_round);
    }
}
?>
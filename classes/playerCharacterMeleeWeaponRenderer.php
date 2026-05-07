<?php
require_once 'playerCharacterWeaponRenderer.php';

require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

require_once __DIR__ . '/rollModifier/meleeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/rollModifier/meleeDamageRmCollectionCalculator.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';
require_once __DIR__ . '/../fa/faChevronIcon.php';

class PlayerCharacterMeleeWeaponRenderer extends PlayerCharacterWeaponRenderer {

    public function render() {
        if (!$this->player_character_weapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
            return '';
        }

        $melee_to_hit_calculator = new MeleeToHitRmCollectionCalculator();
        $melee_to_hit_calculator->setCombatMode($this->getCombatMode());
        $melee_to_hit_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $melee_rm_dmg_calculator = new MeleeDamageRmCollectionCalculator();
        $melee_rm_dmg_calculator->setCombatMode($this->getCombatMode());
        $melee_rm_dmg_calculator->gather($this->character_details, $this->player_character_skill_set, $this->player_character_weapon, $this->attribute_metadata);

        $attacks_per_round = $this->calculateAttacksPerRound($this->player_character_skill_set, $this->player_character_weapon, $this->character_details);

        $weapon_panel_name = 'weapon-' . $this->player_character_weapon->getWeaponId();
		$weapon_panel_icon_name = 'weapon-icon-' . $this->player_character_weapon->getWeaponId();
        
        $weapon_panel  = $this->buildWeaponDetailEntry($this->player_character_weapon, $melee_to_hit_calculator, $melee_rm_dmg_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name);
        $weapon_panel .= $this->buildRmWeaponPanel($melee_to_hit_calculator, $melee_rm_dmg_calculator, $weapon_panel_name);

        return $weapon_panel;
    }

    public function buildWeaponDetailEntry(PlayerCharacterWeapon $player_character_weapon, RmCollectionCalculator $melee_to_hit_calculator, RmCollectionCalculator $melee_damage_calculator, $attacks_per_round, $weapon_panel_name, $weapon_panel_icon_name) {

        $hit_adj = $this->calculateHitAdj($melee_to_hit_calculator);
        $dmg_adj = $this->calculateDmgAdj($melee_damage_calculator);
        $hit_dmg_adj = $hit_adj . '/' . $dmg_adj;

        $weapon_desc = $this->buildRmChevronClickIcon($weapon_panel_name, $weapon_panel_icon_name, $weapon_panel_icon_name) . $player_character_weapon->getWeaponDescription();

        $weapon_detail_entry  = HtmlHelper::buildDivStartTag($this->formatCellStyle());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailLeft', $weapon_desc);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $player_character_weapon->getMeleeWeaponSpeed());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $attacks_per_round);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $player_character_weapon->getMeleeWeaponDamage());
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivTag('rmWeaponDetailCenter', $hit_dmg_adj);
        $weapon_detail_entry .= HtmlHelper::buildDivTag('', '&nbsp;');
        $weapon_detail_entry .= HtmlHelper::buildDivEndTag() . PHP_EOL;

        return $weapon_detail_entry;
    }

    public function calculateAttacksPerRound(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, CharacterDetails $character_details) {
        $attacks_per_round = ATTACKS_PER_ROUND_1_FOR_1;
        $is_specialized = $player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION,$player_character_weapon->getWeaponProficiencyId());
        if ($is_specialized) {
            $character_level = $character_details->getPrimaryClass()->getClassLevel();
            $weapon_subtype = $player_character_weapon->getMeleeWeaponSubtype();
            $weapon_proficiency_id = $player_character_weapon->getWeaponProficiencyId();
            $attacks_per_round = getSpecializedAttacksPerRound($character_level, WEAPON_TYPE_MELEE, $weapon_subtype, $weapon_proficiency_id);
        } else {
            $class_id = $character_details->getBestMeleeClassId();
            $class_level = $character_details->getLevelForClass($class_id);
            $attacks_per_round = getAttacksPerRound($class_id, $class_level, false, false, $player_character_weapon->getWeaponProficiencyId());
        }

        return getAttacksPerRoundDescription($attacks_per_round);
    }
}
?>
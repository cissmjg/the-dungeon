<?php
require_once __DIR__ . '/../webio/craftStatus.php';
require_once __DIR__ . '/../dbio/constants/weapons.php';
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

class WeaponSkillHelper {

    public static function buildCircleKickWeapon() {
        $weapon_detail = [];
        $weapon_detail['player_character_weapon_type'] = WEAPON_TYPE_MELEE;
        $weapon_detail['player_character_weapon_subtype'] = WEAPON_SUBTYPE_MISC_MELEE;
        $weapon_detail['player_character_weapon_id'] = PHP_INT_MAX - 1;
        $weapon_detail['player_character_weapon_proficiency_id'] = FIST;
        $weapon_detail['player_character_weapon_craft_status'] = CRAFT_STATUS_ARTISAN;
        $weapon_detail['player_character_weapon_description'] = 'Circle Kick';
        $weapon_detail['player_character_weapon_is_ready'] = false;
        $weapon_detail['player_character_weapon_location'] = 'Foot';
        $weapon_detail['player_character_weapon_player_note1'] = '';
        $weapon_detail['player_character_weapon_player_note2'] = '';
        $weapon_detail['player_character_weapon_player_note3'] = '';
        $weapon_detail['player_character_weapon_strength_bonus_available'];
        $weapon_detail['player_character_weapon_speed'] = '2/3/4';
        $weapon_detail['player_character_weapon_damage'] = 'd4+1';
        $weapon_detail['player_character_weapon_attacks_per_round'] = 1;
        $weapon_detail['player_character_weapon_number_of_hands'] = 1;

        WeaponSkillHelper::buildNonMagicalProperties($weapon_detail);

        return $weapon_detail;
    }

    public static function buildMantisLeapWeapon() {
        $weapon_detail['player_character_weapon_type'] = WEAPON_TYPE_MELEE;
        $weapon_detail['player_character_weapon_subtype'] = WEAPON_SUBTYPE_MISC_MELEE;
        $weapon_detail['player_character_weapon_id'] = PHP_INT_MAX;
        $weapon_detail['player_character_weapon_proficiency_id'] = FIST;
        $weapon_detail['player_character_weapon_craft_status'] = CRAFT_STATUS_ARTISAN;
        $weapon_detail['player_character_weapon_description'] = 'Mantis Leap';
        $weapon_detail['player_character_weapon_is_ready'] = false;
        $weapon_detail['player_character_weapon_location'] = 'Foot';
        $weapon_detail['player_character_weapon_player_note1'] = '';
        $weapon_detail['player_character_weapon_player_note2'] = '';
        $weapon_detail['player_character_weapon_player_note3'] = '';
        $weapon_detail['player_character_weapon_strength_bonus_available'];
        $weapon_detail['player_character_weapon_speed'] = 'EoM';
        $weapon_detail['player_character_weapon_damage'] = 'd3/d3 * 1.5';
        $weapon_detail['player_character_weapon_attacks_per_round'] = 1;
        $weapon_detail['player_character_weapon_number_of_hands'] = 1;

        WeaponSkillHelper::buildNonMagicalProperties($weapon_detail);

        return $weapon_detail;
    }

    private static function buildNonMagicalProperties(&$weapon_detail) {
        $weapon_detail['player_character_weapon_hit_bonus'] = 0;
        $weapon_detail['player_character_weapon_damage_bonus'] = 0;
        $weapon_detail['player_character_weapon_mastercraft_hit_description'] = 'None';
        $weapon_detail['player_character_weapon_mastercraft_damage_description'] = 'None';
        $weapon_detail['player_character_weapon_spec1_hit_bonus'] = 0;
        $weapon_detail['player_character_weapon_spec1_damage_bonus'] = 0;
        $weapon_detail['player_character_weapon_spec1_description'] = '';
        $weapon_detail['player_character_weapon_spec2_hit_bonus'] = 0;
        $weapon_detail['player_character_weapon_spec2_damage_bonus'] = 0;
        $weapon_detail['player_character_weapon_spec2_description'] = '';
        $weapon_detail['player_character_weapon_spec3_hit_bonus'] = 0;
        $weapon_detail['player_character_weapon_spec3_damage_bonus'] = 0;
        $weapon_detail['player_character_weapon_spec3_description'] = '';
    }
}

?>
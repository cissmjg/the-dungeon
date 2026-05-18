<?php
require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';
class WeaponProficiencyHelper {

    public static function isProficient($weapon_subtype, $weapon_proficiency_id, PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon($weapon_proficiency_id)) {
            return true;
        }

        /*- Look at weapon subtypes -*/

        // Individual weapon proficiencies
        if ($weapon_subtype == WEAPON_SUBTYPE_MISC_MELEE) {
            return false;
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_MISC_MISSILE) {
            return false;
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_POLE_ARM) {
            return false;
        }

        if ($weapon_subtype ==  WEAPON_SUBTYPE_CLUB) {
            return false;
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_HAMMER) {
            return false;
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_SLING) {
            return false;
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_BLOW_GUN) {
            return false;
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_ONE_HANDED_SWORD) {
            return WeaponProficiencyHelper::isProficientWithOneHandedSword($player_character_skill_set);
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_TWO_HANDED_SWORD) {
            return WeaponProficiencyHelper::isProficientWithTwoHandedSword($player_character_skill_set);
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
            return WeaponProficiencyHelper::isProficientWithStringedBow($player_character_skill_set);
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
            return WeaponProficiencyHelper::isProficientWithMechanicalBow($player_character_skill_set);
        }

        if ($weapon_subtype == WEAPON_SUBTYPE_LANCE) {
            return WeaponProficiencyHelper::isProficientWithLance($player_character_skill_set);
        }

        return false;
    }

    private static function isProficientWithOneHandedSword(PlayerCharacterSkillSet $player_character_skill_set) {
        if ($player_character_skill_set->isProficientWithWeapon(LONG_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SCIMITAR)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(BROAD_SWORD)) {
            return true;
        }
        
        if ($player_character_skill_set->isProficientWithWeapon(KHOPESH_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SABRE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(MARINERS_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(PIERCER_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_LIGHTBLADE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_THIN_BLADE)) {
            return true;
        }

        return false;
    }

    private static function isProficientWithTwoHandedSword(PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon(TWO_HANDED_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(FLAMBERGE_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(BASTARD_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(FALCHION_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_COURT_BLADE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(DWARVEN_CLAYMORE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(FALCHION_SWORD)) {
            return true;
        }

        return false;
    }

    private static function isProficientWithStringedBow(PlayerCharacterSkillSet $player_character_skill_set){

        if ($player_character_skill_set->isProficientWithWeapon(LONG_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(LONG_COMPOSITE_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_COMPOSITE_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_CRAFT_BOW)) {
            return true;
        }

        return false;
    }

    private static function isProficientWithMechanicalBow(PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon(LIGHT_CROSSBOW)) {
            return true;
        }
        if ($player_character_skill_set->isProficientWithWeapon(HEAVY_CROSSBOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(GREAT_CROSSBOW)) {
            return true;
        }

        return false;
    }

    private static function isProficientWithLance(PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon(LIGHT_LANCE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(MEDIUM_LANCE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(HEAVY_LANCE)) {
            return true;
        }

        return false;
    }
}
?>
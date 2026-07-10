<?php
require_once __DIR__ . '/../dbio/constants/skills.php';
require_once __DIR__ . '/../dbio/constants/weapons.php';
require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';

require_once __DIR__ . '/../classes/playerCharacterWeaponSet.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/weaponProficiencyId.php';
require_once __DIR__ . '/../webio/weaponDescription.php';
require_once __DIR__ . '/../webio/isProficient.php';
require_once __DIR__ . '/../webio/isReady.php';
require_once __DIR__ . '/../webio/craftStatus.php';
require_once __DIR__ . '/../webio/strengthBonusAvailable.php';
require_once __DIR__ . '/../webio/playerNote1.php';
require_once __DIR__ . '/../webio/playerNote2.php';
require_once __DIR__ . '/../webio/playerNote3.php';
require_once __DIR__ . '/../webio/mastercraftHitDescription.php';
require_once __DIR__ . '/../webio/mastercraftDamageDescription.php';

require_once __DIR__ . '/../webio/meleeWeaponType.php';
require_once __DIR__ . '/../webio/meleeWeaponSubtype.php';
require_once __DIR__ . '/../webio/meleeWeaponSpeed.php';
require_once __DIR__ . '/../webio/meleeWeaponDamage.php';
require_once __DIR__ . '/../webio/meleeAttacksPerRound.php';
require_once __DIR__ . '/../webio/meleeNumberOfHands.php';
require_once __DIR__ . '/../webio/meleeAdditionalText.php';
require_once __DIR__ . '/../webio/meleeHitBonus.php';
require_once __DIR__ . '/../webio/meleeDamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec1HitBonus.php';
require_once __DIR__ . '/../webio/meleeSpec1DamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec1Description.php';
require_once __DIR__ . '/../webio/meleeSpec2HitBonus.php';
require_once __DIR__ . '/../webio/meleeSpec2DamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec2Description.php';
require_once __DIR__ . '/../webio/meleeSpec3HitBonus.php';
require_once __DIR__ . '/../webio/meleeSpec3DamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec3Description.php';

require_once __DIR__ . '/../webio/missileWeaponType.php';
require_once __DIR__ . '/../webio/missileWeaponSubtype.php';
require_once __DIR__ . '/../webio/missileWeaponSpeed.php';
require_once __DIR__ . '/../webio/missileWeaponDamage.php';
require_once __DIR__ . '/../webio/missileAttacksPerRound.php';
require_once __DIR__ . '/../webio/missileNumberOfHands.php';
require_once __DIR__ . '/../webio/missileAdditionalText.php';
require_once __DIR__ . '/../webio/missileHitBonus.php';
require_once __DIR__ . '/../webio/missileDamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec1HitBonus.php';
require_once __DIR__ . '/../webio/missileSpec1DamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec1Description.php';
require_once __DIR__ . '/../webio/missileSpec2HitBonus.php';
require_once __DIR__ . '/../webio/missileSpec2DamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec2Description.php';
require_once __DIR__ . '/../webio/missileSpec3HitBonus.php';
require_once __DIR__ . '/../webio/missileSpec3DamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec3Description.php';
require_once __DIR__ . '/../webio/missileShortRange.php';
require_once __DIR__ . '/../webio/missileMediumRange.php';
require_once __DIR__ . '/../webio/missileLongRange.php';

class WeaponSkillHelper {

    public static function buildCircleKickWeapon($player_name, $character_name) {

        $circle_kick_name = getSkillDescriptionFromSkillId(CIRCLE_KICK);

        $input = [];
        $input[PLAYER_NAME] = $player_name;
        $input[CHARACTER_NAME] = $character_name;
        $input[WEAPON_PROFICIENCY_ID] = FIST;
        $input[WEAPON_DESCRIPTION] = $circle_kick_name;
        $input[WEAPON_LOCATION] = 'Foot';
        $input[IS_PROFICIENT] = 'YES';
        $input[IS_READY] = 'No';
        $input[CRAFT_STATUS] = CRAFT_STATUS_ARTISAN;
        $input[STRENGTH_BONUS_AVAILABLE] = 'No';
        $input[PLAYER_NOTE1] = '';
        $input[PLAYER_NOTE2] = '';
        $input[PLAYER_NOTE3] = '';
        $input[MASTERCRAFT_HIT_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MASTERCRAFT_DAMAGE_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MELEE_WEAPON_TYPE] = WEAPON_TYPE_MELEE;
        $input[MELEE_WEAPON_SUBTYPE] = WEAPON_SUBTYPE_MISC_MELEE;
        $input[MELEE_WEAPON_SPEED] = '2/3/4';
        $input[MELEE_WEAPON_DAMAGE] = 'd4+1';
        $input[MELEE_ATTACKS_PER_ROUND] = 1;
        $input[MELEE_NUMBER_OF_HANDS] = 1;
        $input[MELEE_ADDITIONAL_TEXT] = '';
        WeaponSkillHelper::buildNonMagicalMeleeProperties($input);

        // Melee only weapon
        $input[MISSILE_WEAPON_TYPE] == OPTIONAL_INTEGER_PARAMETER;

        return $input;
    }

    public static function buildMantisLeapWeapon($player_name, $character_name) {

        $mantis_leap_name = getSkillDescriptionFromSkillId(MANTIS_LEAP);

        $input = [];
        $input[PLAYER_NAME] = $player_name;
        $input[CHARACTER_NAME] = $character_name;
        $input[WEAPON_PROFICIENCY_ID] = FIST;
        $input[WEAPON_DESCRIPTION] = $mantis_leap_name;
        $input[WEAPON_LOCATION] = 'Foot';
        $input[IS_PROFICIENT] = 'YES';
        $input[IS_READY] = 'No';
        $input[CRAFT_STATUS] = CRAFT_STATUS_ARTISAN;
        $input[STRENGTH_BONUS_AVAILABLE] = 'No';
        $input[PLAYER_NOTE1] = '';
        $input[PLAYER_NOTE2] = '';
        $input[PLAYER_NOTE3] = '';
        $input[MASTERCRAFT_HIT_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MASTERCRAFT_DAMAGE_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MELEE_WEAPON_TYPE] = WEAPON_TYPE_MELEE;
        $input[MELEE_WEAPON_SUBTYPE] = WEAPON_SUBTYPE_MISC_MELEE;
        $input[MELEE_WEAPON_SPEED] = 'EoM';
        $input[MELEE_WEAPON_DAMAGE] = 'd3/d3 * 1.5';
        $input[MELEE_ATTACKS_PER_ROUND] = 1;
        $input[MELEE_NUMBER_OF_HANDS] = 1;
        $input[MELEE_ADDITIONAL_TEXT] = '';
        WeaponSkillHelper::buildNonMagicalMeleeProperties($input);

        // Melee only weapon
        $input[MISSILE_WEAPON_TYPE] == OPTIONAL_INTEGER_PARAMETER;

        return $input;
    }

    public static function buildThrowAnythingWeapon($player_name, $character_name) {

        $throw_anything_name = getSkillDescriptionFromSkillId(THROW_ANYTHING);

        $input = [];
        $input[PLAYER_NAME] = $player_name;
        $input[CHARACTER_NAME] = $character_name;
        $input[WEAPON_PROFICIENCY_ID] = FIST;
        $input[WEAPON_DESCRIPTION] = $throw_anything_name;
        $input[WEAPON_LOCATION] = 'Hand';
        $input[IS_PROFICIENT] = 'YES';
        $input[IS_READY] = 'No';
        $input[CRAFT_STATUS] = CRAFT_STATUS_ARTISAN;
        $input[STRENGTH_BONUS_AVAILABLE] = 'No';
        $input[PLAYER_NOTE1] = '';
        $input[PLAYER_NOTE2] = '';
        $input[PLAYER_NOTE3] = '';
        $input[MASTERCRAFT_HIT_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MASTERCRAFT_DAMAGE_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MISSILE_WEAPON_TYPE] = WEAPON_TYPE_MISSILE;
        $input[MISSILE_WEAPON_SUBTYPE] = WEAPON_SUBTYPE_MISC_MISSILE;
        $input[MISSILE_WEAPON_SPEED] = '???';
        $input[MISSILE_WEAPON_DAMAGE] = '???';
        $input[MISSILE_ATTACKS_PER_ROUND] = 1;
        $input[MISSILE_ADDITIONAL_TEXT] = 'Speed/Damage at DM discretion';
        $input[MISSILE_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC1_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC1_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC1_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MISSILE_SPEC2_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC2_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC2_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MISSILE_SPEC3_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC3_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER;
        $input[MISSILE_SPEC3_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MISSILE_SHORT_RANGE] = 2;
        $input[MISSILE_MEDIUM_RANGE] = 4;
        $input[MISSILE_LONG_RANGE] = 6;

        return $input;
    }

    private static function buildNonMagicalMeleeProperties(&$input) {

        $input[MELEE_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC1_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC1_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC1_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MELEE_SPEC2_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC2_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC2_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
        $input[MELEE_SPEC3_HIT_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC3_DAMAGE_BONUS] = OPTIONAL_INTEGER_PARAMETER;
        $input[MELEE_SPEC3_DESCRIPTION] = OPTIONAL_STRING_PARAMETER;
    }
}

?>
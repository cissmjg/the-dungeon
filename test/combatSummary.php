<?php
    require_once __DIR__ . '/../classes/characterDetails.php';
    require_once __DIR__ . '/../classes/playerCharacterSkill.php';
    require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';
    require_once __DIR__ . '/../classes/PlayerCharacterWeapon.php';
    require_once __DIR__ . '/../classes/playerCharacterWeaponSet.php';
    require_once __DIR__ . '/../classes/attributeMetadata.php';
    require_once __DIR__ . '/../classes/TwoWeaponFightingConfigurationSet.php';

    require_once __DIR__ . '/../classes/playerCharacterMeleeWeaponRenderer.php';
    require_once __DIR__ . '/../classes/playerCharacterMissileWeaponRenderer.php';
    require_once __DIR__ . '/../classes/combatSummaryRenderer.php';

    require_once __DIR__ . '/../classes/rollModifier/missileArcherLongRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherLongSwiftwingRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherMediumRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherMediumSwiftwingRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherPointBlankToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherShortRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierLongRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierMediumRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierMediumSwiftwingRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierShortRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileLongRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileLongSwiftwingRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileMediumRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileMediumSwiftwingRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missilePointBlankToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileShortRangeToHitRmCollectionCalculator.php';

    require_once __DIR__ . '/../classes/rollModifier/missileArcherLongRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherMediumRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherPointBlankDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherShortRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileLongRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileMediumRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missilePointBlankDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileShortRangeDamageRmCollectionCalculator.php';

    require_once __DIR__ . '/../classes/rollModifier/meleeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/meleeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/meleeElvenCavalierToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/meleeElvenCavalierDamageRmCollectionCalculator.php';

    require_once __DIR__ . '/../dbio/constants/skills.php';
    require_once __DIR__ . '/../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
    require_once __DIR__ . '/../rules/attacksPerRound.php';

    $character_name = $argv[1];
    if (empty($character_name)) {
        echo 'Character name is required' . PHP_EOL;
        exit;
    }

    // PlayerCharacterDetails
    $character_details_file_name = sprintf("data/%s_character_details.json", $character_name);
    $character_details_json = json_decode(file_get_contents($character_details_file_name));
    $character_details = new CharacterDetails();
    $character_details->fromJSON($character_details_json);

    // PlayerCharacterSkillSet
    $skill_set_file_name = sprintf("data/%s_skill_set.json", $character_name);
    $skill_set_json = json_decode(file_get_contents($skill_set_file_name));
    $player_character_skill_set = new PlayerCharacterSkillSet();
    $player_character_skill_set->fromJSON($skill_set_json);

    // PlayerCharacterWeaponSet
    $weapon_set_file_name = sprintf("data/%s_weapon_set.json", $character_name);
    $weapon_set_json = json_decode(file_get_contents($weapon_set_file_name));
    $player_character_weapon_set = new playerCharacterWeaponSet();
    $player_character_weapon_set->fromJSON($weapon_set_json->playerCharacterWeaponList, $player_character_skill_set);

    // AttributeMetadata
    $attribute_metadata = new AttributeMetadata($character_details);

    $combat_summary_renderer = new CombatSummaryRenderer($player_character_weapon_set, $player_character_skill_set, $character_details, $attribute_metadata);
    $combat_summary_renderer->render();

?>
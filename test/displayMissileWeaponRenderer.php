<?php
    require_once __DIR__ . '/../classes/playerCharacterMissileWeaponRenderer.php';
    require_once __DIR__ . '/../classes/twoWeaponFightingConfigurationSet.php';
    require_once __DIR__ . '/../classes/playerCharacterWeaponSet.php';
    require_once __DIR__ . '/../classes/playerCharacterWeapon.php';
    require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';
    require_once __DIR__ . '/../classes/characterDetails.php';
    require_once __DIR__ . '/../classes/attributeMetadata.php';
    require_once __DIR__ . '/../classes/rowClassManager.php';

    require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';

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
    $player_character_weapon_set = new PlayerCharacterWeaponSet();
    $player_character_weapon_set->fromJSON($weapon_set_json->playerCharacterWeaponList, $player_character_skill_set);

    $two_weapon_config_file_name = sprintf("data/%s_two_weapon_configuration_set.json", $character_name);
    $two_weapon_config_file = file_get_contents($two_weapon_config_file_name);
    $two_weapon_config_json = json_decode($two_weapon_config_file);
    $two_weapon_fighting_configuration_set = new TwoWeaponFightingConfigurationSet();
    $two_weapon_fighting_configuration_set->fromJSON($two_weapon_config_json);

    // AttributeMetadata
    $attribute_metadata = new AttributeMetadata($character_details);

    $row_class_manager = new RowClassManager();

    foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
        if ($player_character_weapon->getWeaponId() == 73) {
            $player_character_missile_renderer = new PlayerCharacterMissileWeaponRenderer($player_character_weapon, $player_character_skill_set, $character_details, $attribute_metadata, $row_class_manager);
            $player_character_missile_renderer->setCombatMode(COMBAT_MODE_MOUNTED);
            echo $player_character_missile_renderer->render();
        }
    }
?>
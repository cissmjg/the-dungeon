<?php
    require_once __DIR__ . '/../classes/attacksPerRoundCalculator.php';
    require_once __DIR__ . '/../classes/characterDetails.php';
    require_once __DIR__ . '/../classes/playerCharacterSkill.php';
    require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';
    require_once __DIR__ . '/../classes/PlayerCharacterWeapon.php';
    require_once __DIR__ . '/../classes/playerCharacterWeaponSet.php';
    require_once __DIR__ . '/../classes/attributeMetadata.php';
    require_once __DIR__ . '/../classes/twoWeaponFightingConfigurationSet.php';

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
    $player_character_weapon_set = new playerCharacterWeaponSet();
    $player_character_weapon_set->fromJSON($weapon_set_json->playerCharacterWeaponList, $player_character_skill_set);

    $two_weapon_config_file_name = sprintf("data/%s_two_weapon_configuration_set.json", $character_name);
    $two_weapon_config_file = file_get_contents($two_weapon_config_file_name);
    $two_weapon_config_json = json_decode($two_weapon_config_file);
    $two_weapon_fighting_configuration_set = new TwoWeaponFightingConfigurationSet();
    $two_weapon_fighting_configuration_set->fromJSON($two_weapon_config_json);

    // AttributeMetadata
    $attribute_metadata = new AttributeMetadata($character_details);
                
    $best_melee_class_id = $character_details->getBestMeleeClassId();
    $class_description = $character_details->getDescriptionForClassId($best_melee_class_id);
    $character_level = $character_details->getLevelForClass($best_melee_class_id);

    $header = sprintf("\n%' 32s %s %d level\n", $character_name, $class_description, $character_level);
    echo $header;
    echo str_repeat("-", 80) . PHP_EOL;

    foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
        if ($player_character_weapon->getWeaponId() != 0) {
            $is_specialized = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $player_character_weapon->getWeaponProficiencyId())) > 0;
            $spec_display = $is_specialized ? 'Y' : 'N';
            $attacks_per_round_calculator = new AttacksPerRoundCalculator($character_details, $player_character_skill_set, $player_character_weapon, COMBAT_MODE_UNMOUNTED);

            try{
                if ($player_character_weapon->getMeleeWeaponType() == WEAPON_TYPE_MELEE) {
                    $attacks_per_round = $attacks_per_round_calculator->getAttacksPerRound(WEAPON_TYPE_MELEE);
                    if (empty($attacks_per_round)) {
                        echo $player_character_weapon->getWeaponDescription() . ' no attacks per round. ID: ' . $player_character_weapon->getWeaponId() . PHP_EOL;
                    } else {
                        $output = sprintf("%' 26s %s %s %3d Melee\n", $player_character_weapon->getWeaponDescription(), $spec_display, $attacks_per_round->value, $player_character_weapon->getWeaponId());
                        echo $output;
                    }
                }

                if ($player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                    $attacks_per_round = $attacks_per_round_calculator->getAttacksPerRound(WEAPON_TYPE_MISSILE);
                    if (empty($attacks_per_round)) {
                        echo $player_character_weapon->getWeaponDescription() . ' no attacks per round. ID: ' . $player_character_weapon->getWeaponId() . PHP_EOL;
                    } else {
                        $output = sprintf("%' 26s %s %s %3d Missile\n", $player_character_weapon->getWeaponDescription(), $spec_display, $attacks_per_round->value, $player_character_weapon->getWeaponId());
                        echo $output;
                    }
                }
            } catch(Exception $e) {
                $errors[] = "Exception in getAttacksPerRound : " . $e->getMessage();
            }

        }
    }
?>
<?php
    require_once __DIR__ . '/../classes/characterDetails.php';
    require_once __DIR__ . '/../classes/playerCharacterSkill.php';
    require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';
    require_once __DIR__ . '/../classes/PlayerCharacterWeapon.php';
    require_once __DIR__ . '/../classes/playerCharacterWeaponSet.php';
    require_once __DIR__ . '/../classes/attributeMetadata.php';
    require_once __DIR__ . '/../classes/rollModifier/missilePointBlankToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missilePointBlankDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileShortRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileShortRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileMediumRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileMediumRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileLongRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileLongRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierShortRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierMediumRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileElvenCavalierLongRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherPointBlankToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherPointBlankDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherShortRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherShortRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherMediumRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherMediumRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherLongRangeToHitRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/missileArcherLongRangeDamageRmCollectionCalculator.php';
    require_once __DIR__ . '/../classes/rollModifier/rmUIContainer.php';
    require_once __DIR__ . '/../dbio/constants/skills.php';
    require_once __DIR__ . '/../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../dbio/constants/cavalierCombatMode.php';
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

    $primary_class = $character_details->getPrimaryClass();
    if ($primary_class->getClassId() == ELVEN_CAVALIER) {
        $class_id = $character_details->getBestMeleeClassId();
        $class_level = $character_details->getLevelForClass($class_id);
        echo '========================================= Missile Mounted Long ==========================================' . PHP_EOL;
        echo 'Missile Class: [' . $character_details->getDescriptionForClassId($class_id) . '] Level: [' . $class_level . ']' . PHP_EOL;
        foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
            if ($player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                $mounted_missile_rm_hit_calculator = new MissileElvenCavalierLongRangeToHitRmCollectionCalculator();
                $mounted_missile_rm_hit_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $mounted_missile_to_hit_modification = $mounted_missile_rm_hit_calculator->aggregate();

                $mounted_missile_rm_damage_calculator = new MissileLongRangeDamageRmCollectionCalculator();
                $mounted_missile_rm_damage_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $mounted_missile_damage_modification = $mounted_missile_rm_damage_calculator->aggregate();

                $is_preferred = $player_character_skill_set->isWeaponPreferred($player_character_weapon->getWeaponProficiencyId());
                $attacks_per_round = getAttacksPerRound($class_id, $class_level, $is_preferred, true, $player_character_weapon->getWeaponProficiencyId());

                $attacks_per_round_description = getAttacksPerRoundDescription($attacks_per_round);
                $full_weapon_description = sprintf("Weapon : [%s] [%d] to hit [%d] damage [%s] attacks/round", $player_character_weapon->getWeaponDescription(), $mounted_missile_to_hit_modification, $mounted_missile_damage_modification, $attacks_per_round_description);
                echo '==================================================================================================' . PHP_EOL;
                echo $full_weapon_description . PHP_EOL;
                echo 'Hit Bonuses' . PHP_EOL;
                foreach($mounted_missile_rm_hit_calculator->getRmCollection() AS $missile_rm_hit) {
                    echo '    ' . $missile_rm_hit->getRMDescription() . ' ' . $missile_rm_hit->getRMData() . PHP_EOL;
                }

                echo 'Damage Bonuses' . PHP_EOL;
                foreach($mounted_missile_rm_damage_calculator->getRmCollection() AS $melee_rm_damage) {
                    echo '    ' . $melee_rm_damage->getRMDescription() . ' ' . $melee_rm_damage->getRMData() . PHP_EOL;
                }
            }
        }

        echo '========================================= Missile Unmounted Long ========================================' . PHP_EOL;
        echo 'Melee Class: [' . $character_details->getDescriptionForClassId($class_id) . '] Level: [' . $class_level . ']' . PHP_EOL;
        foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
            if ($player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                $mounted_missile_rm_hit_calculator = new MissileElvenCavalierLongRangeToHitRmCollectionCalculator();
                $mounted_missile_rm_hit_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $mounted_missile_to_hit_modification = $mounted_missile_rm_hit_calculator->aggregate();

                $mounted_missile_rm_damage_calculator = new MissileLongRangeDamageRmCollectionCalculator();
                $mounted_missile_rm_damage_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $mounted_missile_damage_modification = $mounted_missile_rm_damage_calculator->aggregate();

                $is_preferred = $player_character_skill_set->isWeaponPreferred($player_character_weapon->getWeaponProficiencyId());
                $attacks_per_round = getAttacksPerRound($class_id, $class_level, $is_preferred, false, $player_character_weapon->getWeaponProficiencyId());

                $attacks_per_round_description = getAttacksPerRoundDescription($attacks_per_round);
                $full_weapon_description = sprintf("Weapon : [%s] [%d] to hit [%d] damage [%s] attacks/round", $player_character_weapon->getWeaponDescription(), $mounted_missile_to_hit_modification, $mounted_missile_damage_modification, $attacks_per_round_description, $attacks_per_round_description);
                echo '==================================================================================================' . PHP_EOL;
                echo $full_weapon_description . PHP_EOL;
                echo 'Hit Bonuses' . PHP_EOL;
                foreach($mounted_missile_rm_hit_calculator->getRmCollection() AS $missile_rm_hit) {
                    echo '    ' . $missile_rm_hit->getRMDescription() . ' ' . $missile_rm_hit->getRMData() . PHP_EOL;
                }

                echo 'Damage Bonuses' . PHP_EOL;
                foreach($mounted_missile_rm_damage_calculator->getRmCollection() AS $melee_rm_damage) {
                    echo '    ' . $melee_rm_damage->getRMDescription() . ' ' . $melee_rm_damage->getRMData() . PHP_EOL;
                }
            }
        }
    } else if ($character_details->containsClassId(ARCHER) || $character_details->containsClassId(ARCHER_RANGER)) {
        $class_id = $character_details->getBestMeleeClassId();
        $class_level = $character_details->getLevelForClass($class_id);
        echo '========================================= Archer Missile Long ==================================================' . PHP_EOL;
        echo 'Missile Class: [' . $character_details->getDescriptionForClassId($class_id) . '] Level: [' . $class_level . ']' . PHP_EOL;

        foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
            if ($player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                $missile_rm_hit_calculator = new MissileArcherLongRangeToHitRmCollectionCalculator();
                $missile_rm_hit_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $missile_to_hit_modification = $missile_rm_hit_calculator->aggregate();

                $missile_rm_damage_calculator = new MissileArcherLongRangeDamageRmCollectionCalculator();
                $missile_rm_damage_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $missile_damage_modification = $missile_rm_damage_calculator->aggregate();

                $weapon_subtype = $player_character_weapon->getMissileWeaponSubtype();
                $weapon_proficiency_id = $player_character_weapon->getWeaponProficiencyId();
                $attacks_per_round = getSpecializedAttacksPerRound($class_level, WEAPON_TYPE_MISSILE, $weapon_subtype, $weapon_proficiency_id);

                $attacks_per_round_description = getAttacksPerRoundDescription($attacks_per_round);
                $full_weapon_description = sprintf("Weapon : [%s] [%d] to hit [%d] damage [%s] attack/round", $player_character_weapon->getWeaponDescription(), $missile_to_hit_modification, $missile_damage_modification, $attacks_per_round_description);
                echo '==================================================================================================' . PHP_EOL;
                echo $full_weapon_description . PHP_EOL;
                echo 'Hit Bonuses' . PHP_EOL;
                foreach($missile_rm_hit_calculator->getRmCollection() AS $missile_rm_hit) {
                    echo '    ' . $missile_rm_hit->getRMDescription() . ' ' . $missile_rm_hit->getRMData() . PHP_EOL;
                }

                $rm_ui_hit_container = new RmUIContainer($missile_rm_hit_calculator->getRmCollection(), 'To Hit');
                echo $rm_ui_hit_container->render();

                echo 'Damage Bonuses' . PHP_EOL;
                foreach($missile_rm_damage_calculator->getRmCollection() AS $melee_rm_damage) {
                    echo '    ' . $melee_rm_damage->getRMDescription() . ' ' . $melee_rm_damage->getRMData() . PHP_EOL;
                }

                $rm_ui_damage_container = new RmUIContainer($missile_rm_damage_calculator->getRmCollection(), 'Damage');
                echo $rm_ui_damage_container->render();
            }
        }
    } else {
        $class_id = $character_details->getBestMeleeClassId();
        $class_level = $character_details->getLevelForClass($class_id);
        echo '========================================= Missile Long ==================================================' . PHP_EOL;
        echo 'Missile Class: [' . $character_details->getDescriptionForClassId($class_id) . '] Level: [' . $class_level . ']' . PHP_EOL;
        foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
            if ($player_character_weapon->getMissileWeaponType() == WEAPON_TYPE_MISSILE) {
                $missile_rm_hit_calculator = new MissileLongRangeToHitRmCollectionCalculator();
                $missile_rm_hit_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $missile_to_hit_modification = $missile_rm_hit_calculator->aggregate();

                $missile_rm_damage_calculator = new MissileLongRangeDamageRmCollectionCalculator();
                $missile_rm_damage_calculator->gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $missile_damage_modification = $missile_rm_damage_calculator->aggregate();

                $attacks_per_round = '';
                $is_specialized = $player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION,$player_character_weapon->getWeaponProficiencyId());
                if ($is_specialized) {
                    $character_level = $primary_class->getClassLevel();
                    $weapon_subtype = $player_character_weapon->getMissileWeaponSubtype();
                    $weapon_proficiency_id = $player_character_weapon->getWeaponProficiencyId();
                    $attacks_per_round = getSpecializedAttacksPerRound($character_level, WEAPON_TYPE_MISSILE, $weapon_subtype, $weapon_proficiency_id);
                } else {
                    $class_id = $character_details->getBestMeleeClassId();
                    $class_level = $character_details->getLevelForClass($class_id);
                    $attacks_per_round = getAttacksPerRound($class_id, $class_level, false, false, $player_character_weapon->getWeaponProficiencyId());
                }

                $attacks_per_round_description = getAttacksPerRoundDescription($attacks_per_round);
                $full_weapon_description = sprintf("Weapon : [%s] [%d] to hit [%d] damage [%s] attack/round", $player_character_weapon->getWeaponDescription(), $missile_to_hit_modification, $missile_damage_modification, $attacks_per_round_description);
                echo '==================================================================================================' . PHP_EOL;
                echo $full_weapon_description . PHP_EOL;
                echo 'Hit Bonuses' . PHP_EOL;
                foreach($missile_rm_hit_calculator->getRmCollection() AS $missile_rm_hit) {
                    echo '    ' . $missile_rm_hit->getRMDescription() . ' ' . $missile_rm_hit->getRMData() . PHP_EOL;
                }

                $rm_ui_hit_container = new RmUIContainer($missile_rm_hit_calculator->getRmCollection(), 'To Hit');
                echo $rm_ui_hit_container->render();

                echo 'Damage Bonuses' . PHP_EOL;
                foreach($missile_rm_damage_calculator->getRmCollection() AS $melee_rm_damage) {
                    echo '    ' . $melee_rm_damage->getRMDescription() . ' ' . $melee_rm_damage->getRMData() . PHP_EOL;
                }

                $rm_ui_damage_container = new RmUIContainer($missile_rm_damage_calculator->getRmCollection(), 'Damage');
                echo $rm_ui_damage_container->render();
            }
        }
    }

?>
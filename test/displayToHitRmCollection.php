<?php
    require_once __DIR__ . '/../classes/characterDetails.php';
    require_once __DIR__ . '/../classes/playerCharacterSkill.php';
    require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';

    $character_name = $argv[1];

    $character_details_file_name = sprintf("data/%s_character_details.json", $character_name);
    $skill_set_file_name = sprintf("data/%s_skill_set.json", $character_name);
    $weapon_set_file_name = sprintf("data/%s_weapon_set.json", $character_name);

    $weapon_set_json = json_decode($weapon_set_file_name);

    // PlayerCharacterDetails
    $character_details_json = json_decode(file_get_contents($character_details_file_name));
    $character_details = new CharacterDetails();
    $character_details->fromJSON($character_details_json);

    echo 'Name: [' . $character_details->getCharacterName() . ']' . PHP_EOL;
    echo 'Wisdom: [' . $character_details->formatWisdom() . ']' . PHP_EOL;
    echo 'Parents Married: [' . var_export($character_details->getParentsMarried(), true) . ']' . PHP_EOL;
    foreach($character_details->getCharacterClasses() AS $character_class) {
        echo '    Class Name:  [' . $character_class->getClassName() . ']' . PHP_EOL;
        echo '    Class Level: [' . $character_class->getClassLevel() . ']' . PHP_EOL;
        echo '    Class XP:    [' . $character_class->getNumberOfExperiencePoints() . ']' . PHP_EOL;
        echo '    Class ID:    [' . $character_class->getClassId() . ']' . PHP_EOL;
        echo '    Spell Classes : [' . print_r($character_class->getSpellClasses(), true) . ']' . PHP_EOL;
    }

    // PlayerCharacterSkillSet
    $skill_set_json = json_decode(file_get_contents($skill_set_file_name));
    $player_character_skill_set = new PlayerCharacterSkillSet();
    $player_character_skill_set->fromJSON($skill_set_json);

    echo 'Skills' . PHP_EOL;
    $all_skills = $player_character_skill_set->getPlayerCharacterSkills();
    foreach($all_skills AS $player_character_skill) {
        echo '    Skill ID:         [' . $player_character_skill->getPlayerCharacterSkillId() . ']' . PHP_EOL;
        echo '    Skill Catalog ID: [' . $player_character_skill->getSkillCatalogId() . ']' . PHP_EOL;
        echo '    Skill Name:       [' . $player_character_skill->getPlayerCharacterSkillName() . ']' . PHP_EOL;
        echo '    Weapon1:          [' . $player_character_skill->getWeaponProficiencyId() . ']' . PHP_EOL;
        echo '    Weapon2:          [' . $player_character_skill->getWeapon2ProficiencyId() . ']' . PHP_EOL;
        echo '    Cavalier3:        [' . $player_character_skill->getIsPreferredCavalierLevel3() . ']' . PHP_EOL;
        echo '    Cavalier5:        [' . $player_character_skill->getIsPreferredCavalierLevel5() . ']' . PHP_EOL;
        echo '    ECavalier4:       [' . $player_character_skill->getIsPreferredElvenCavalierLevel4() . ']' . PHP_EOL;
        echo '    ECavalier6:       [' . $player_character_skill->getIsPreferredElvenCavalierLevel6() . ']' . PHP_EOL;
        
        echo  PHP_EOL;
    }
?>
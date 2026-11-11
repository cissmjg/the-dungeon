<?php
    require_once __DIR__ . '/../classes/skills/formIdLookup.php';
    require_once __DIR__ . '/../classes/skillCatalog.php';
    require_once __DIR__ . '/../classes/characterDetails.php';
    require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';

    require_once __DIR__ . '/../classes/skills/agileAthlete.php';
    require_once __DIR__ . '/../classes/skills/climb.php';
    require_once __DIR__ . '/../classes/skills/decipherScript.php';
    require_once __DIR__ . '/../classes/skills/hide.php';
    require_once __DIR__ . '/../classes/skills/moveSilently.php';
    require_once __DIR__ . '/../classes/skills/openLock.php';
    require_once __DIR__ . '/../classes/skills/pickPocket.php';
    require_once __DIR__ . '/../classes/skills/stealthy.php';

    $character_name = $argv[1];
    if (empty($character_name)) {
        echo 'Character name is required' . PHP_EOL;
        exit;
    }
    // SkillCatalog
    $skill_catalog_file_name = "skill_catalog.json";
    $skill_catalog_json = json_decode(file_get_contents($skill_catalog_file_name));
    $skill_catalog = new SkillCatalog();
    $skill_catalog->fromJSON($skill_catalog_json);

    // FormIdLookup
    $delete_form_id = 'deleteWeaponTalent';
    $delete_weapon_talent_id = $delete_form_id . '-' . 'weapon-id';

    $add_form_id = 'addWeaponTalent';
    $add_weapon_talent_form_id = $add_form_id . '-' . 'weapon-id';;

    $form_id_lookup = new FormIdLookup($delete_form_id, $delete_weapon_talent_id, $add_form_id, $add_weapon_talent_form_id, 'weapon_proficiency', 'weapon3_proficiency', 'weapon_specialization_type_id');
    
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

    /*
    $agile_athlete = new AgileAthlete($skill_catalog, $form_id_lookup);
    echo $agile_athlete->render($character_details, $player_character_skill_set);
    echo $agile_athlete->dump();
    */

    /*
    $climb = new Climb($skill_catalog, $form_id_lookup);
    echo $climb->render($character_details, $player_character_skill_set);
    echo $climb->dump();
    */

    /*
    $decipher_script = new DecipherScript($skill_catalog, $form_id_lookup);
    echo $decipher_script->render($character_details, $player_character_skill_set);
    echo $decipher_script->dump();
    */

    /*
    $hide = new Hide($skill_catalog, $form_id_lookup);
    echo $hide->render($character_details, $player_character_skill_set);
    echo $hide->dump();
    */

    /*
    $move_silently = new MoveSilently($skill_catalog, $form_id_lookup);
    echo $move_silently->render($character_details, $player_character_skill_set);
    echo $move_silently->dump();
    */

    /*
    $open_lock = new OpenLock($skill_catalog, $form_id_lookup);
    echo $open_lock->render($character_details, $player_character_skill_set);
    echo $open_lock->dump();
    */

    /*
    $pick_pocket = new PickPocket($skill_catalog, $form_id_lookup);
    echo $pick_pocket->render($character_details, $player_character_skill_set);
    echo $pick_pocket->dump();
    */

    $stealthy = new Stealthy($skill_catalog, $form_id_lookup);
    echo $stealthy->render($character_details, $player_character_skill_set);
    echo $stealthy->dump();
?>
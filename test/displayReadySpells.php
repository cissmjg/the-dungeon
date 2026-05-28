<?php
    require_once __DIR__ . '/../classes/playerCharacterSpellPool.php';
    require_once __DIR__ . '/../classes/playerCharacterReadySpell.php';
    require_once __DIR__ . '/../classes/playerCharacterReadySpellSet.php';
    require_once __DIR__ . '/../classes/readySpellFormIdLookup.php';
    require_once __DIR__ . '/../classes/playerCharacterReadySpellRenderer.php';
    require_once __DIR__ . '/../classes/rowClassManager.php';

    require_once __DIR__ . '/../webio/spellSlotId.php';
    require_once __DIR__ . '/../webio/spellCatalogId.php';
    require_once __DIR__ . '/../webio/spellCastingTime.php';
    require_once __DIR__ . '/../webio/spellDuration.php';

    $character_name = $argv[1];
    if (empty($character_name)) {
        echo 'Character name is required' . PHP_EOL;
        exit;
    }

    const CAST_SPELL_SLOT_FORM_ID = 'cast-spell';
    const CAST_SPELL_SLOT_ID = CAST_SPELL_SLOT_FORM_ID . '-' . SPELL_SLOT_ID;
    const CAST_SPELL_CASTING_TIME_ID = CAST_SPELL_SLOT_FORM_ID . '-' . SPELL_CASTING_TIME;
    const CAST_SPELL_DURATION_ID = CAST_SPELL_SLOT_FORM_ID . '-' . SPELL_DURATION;

    const UPDATE_SPELL_SLOT_FORM_ID = 'update-spell-slot';
    const UPDATE_SLOT_SPELL_SLOT_ID = UPDATE_SPELL_SLOT_FORM_ID . '-' . SPELL_SLOT_ID;
    const UPDATE_SLOT_SPELL_CATALOG_ID = UPDATE_SPELL_SLOT_FORM_ID . '-' . SPELL_CATALOG_ID;
    const UPDATE_SLOT_SPELL_CHARACTER_CLASS_NAME = UPDATE_SPELL_SLOT_FORM_ID . CHARACTER_CLASS_NAME;
    const UPDATE_SLOT_SPELL_LEVEL = UPDATE_SPELL_SLOT_FORM_ID . SPELL_LEVEL;

    const RECLAIM_CANTRIPS_FORM_ID = 'reclaim-cantrips';
    const RECLAIM_CANTRIPS_SLOT_ID = RECLAIM_CANTRIPS_FORM_ID . '-' . SPELL_SLOT_ID;

    const RESET_SLOT_FORM_ID = 'reset-slot';
    const RESET_SLOT_SPELL_SLOT_ID = RESET_SLOT_FORM_ID . '-' . SPELL_SLOT_ID;

    const STOP_CASTING_FORM_ID = 'stop-casting';
    const STOP_CASTING_SLOT_ID = STOP_CASTING_FORM_ID . '-' . SPELL_SLOT_ID;

    const STOP_RUNNING_FORM_ID = 'stop-running';
    const STOP_RUNNING_SLOT_ID = STOP_RUNNING_FORM_ID . '-' . SPELL_SLOT_ID;

    $ready_spell_form_id_lookup = new ReadySpellFormIdLookup();
    $ready_spell_form_id_lookup->setCastSpellSlotFormId(CAST_SPELL_SLOT_FORM_ID);
    $ready_spell_form_id_lookup->setCastSpellSlotDuration(CAST_SPELL_DURATION_ID);
    $ready_spell_form_id_lookup->setCastSpellSlotCastingTime(CAST_SPELL_CASTING_TIME_ID);
    $ready_spell_form_id_lookup->setCastSpellSlotId(CAST_SPELL_SLOT_ID);

    $ready_spell_form_id_lookup->setUpdateSpellSlotFormId(UPDATE_SPELL_SLOT_FORM_ID);
    $ready_spell_form_id_lookup->setUpdateSpellSlotSpellCatalogId(UPDATE_SLOT_SPELL_CATALOG_ID);
    $ready_spell_form_id_lookup->setUpdateSpellSlotId(UPDATE_SLOT_SPELL_SLOT_ID);
    $ready_spell_form_id_lookup->setUpdateSpellSlotCharacterClassName(UPDATE_SLOT_SPELL_CHARACTER_CLASS_NAME);
    $ready_spell_form_id_lookup->setUpdateSpellSlotSpellLevel(UPDATE_SLOT_SPELL_LEVEL);

    $ready_spell_form_id_lookup->setReclaimCantripsFormId(RECLAIM_CANTRIPS_FORM_ID);
    $ready_spell_form_id_lookup->setReclaimCantripsSlotId(RECLAIM_CANTRIPS_SLOT_ID);

    $ready_spell_form_id_lookup->setResetSlotFormId(RESET_SLOT_FORM_ID);
    $ready_spell_form_id_lookup->setResetSlotId(RESET_SLOT_SPELL_SLOT_ID);

    $ready_spell_form_id_lookup->setStopCastingSlotFormId(STOP_CASTING_FORM_ID);
    $ready_spell_form_id_lookup->setStopCastingSlotId(STOP_CASTING_SLOT_ID);

    $ready_spell_form_id_lookup->setStopRunningSlotFormId(STOP_RUNNING_FORM_ID);
    $ready_spell_form_id_lookup->setStopRunningSlotId(STOP_RUNNING_SLOT_ID);

    $ready_spells_file_name = sprintf("data/%s_ready_spells.json", $character_name);
    $ready_spells_json = json_decode(file_get_contents($ready_spells_file_name));

    $player_character_ready_spell_set = new PlayerCharacterReadySpellSet();
    $player_character_ready_spell_set->fromJSON($ready_spells_json->playerCharacterReadySpellList);

    $spell_pool_file_name = sprintf("data/%s_spell_pool.json", $character_name);
    $spell_pool_json = json_decode(file_get_contents($spell_pool_file_name));

    $player_character_spell_pool = new PlayerCharacterSpellPool();
    $player_character_spell_pool->fromJSON($spell_pool_json->playerCharacterPoolSpellList);

    $row_class_manager = new RowClassManager();
    $row_class_manager->setDefaultClassName('readySpell');
    $row_class_manager->setAlternateClassName('readySpellAlt');

    foreach ($player_character_ready_spell_set->getSpellMap() as $character_class_name => $spellsByClass) {
        echo 'Character Class: ' . $character_class_name . PHP_EOL;
        foreach($spellsByClass as $spell_type => $spellsByClassType) {
            echo '  Spell Type: ' . $spell_type . PHP_EOL;
            foreach($spellsByClassType as $spell_level => $spellsByClassTypeLevel) {
                echo '    Spell Level: ' . $spell_level . PHP_EOL;
                foreach($spellsByClassTypeLevel AS $ready_spell) {
                    $ready_spell_renderer = new PlayerCharacterReadySpellRenderer($ready_spell, $player_character_spell_pool, $ready_spell_form_id_lookup, $row_class_manager);
                    echo $ready_spell_renderer->render();
                    echo PHP_EOL;
                }
            }
        }
    }

    // foreach($player_character_ready_spell_set->getAll() AS $player_character_ready_spell) {
    //    echo 'Spell Name: ' . $player_character_ready_spell->getSpellName() . ' level: ' . $player_character_ready_spell->getPlayerSlotLevel() . PHP_EOL;
    // }
    ?>
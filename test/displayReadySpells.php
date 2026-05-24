<?php
    require_once __DIR__ . '/../classes/playerCharacterReadySpell.php';
    require_once __DIR__ . '/../classes/playerCharacterReadySpellSet.php';

    $character_name = $argv[1];
    if (empty($character_name)) {
        echo 'Character name is required' . PHP_EOL;
        exit;
    }

    $ready_spells_file_name = sprintf("data/%s_ready_spells.json", $character_name);
    $ready_spells_json = json_decode(file_get_contents($ready_spells_file_name));

    $player_character_ready_spell_set = new PlayerCharacterReadySpellSet();
    $player_character_ready_spell_set->fromJSON($ready_spells_json->playerCharacterReadySpellList);

    foreach ($player_character_ready_spell_set->getSpellMap() as $character_class_name => $spellsByClass) {
        echo 'Character Class: ' . $character_class_name . PHP_EOL;
        foreach($spellsByClass as $spell_type => $spellsByClassType) {
            echo '  Spell Type: ' . $spell_type . PHP_EOL;
            foreach($spellsByClassType as $spell_level => $spellsByClassTypeLevel) {
                echo '    Spell Level: ' . $spell_level . PHP_EOL;
                foreach($spellsByClassTypeLevel AS $ready_spell) {
                    echo '      Spell Name: ' . $ready_spell->getSpellName() . ' level: ' . $ready_spell->getPlayerSlotLevel() . PHP_EOL;
                }
            }
        }
    }

    // foreach($player_character_ready_spell_set->getAll() AS $player_character_ready_spell) {
    //    echo 'Spell Name: ' . $player_character_ready_spell->getSpellName() . ' level: ' . $player_character_ready_spell->getPlayerSlotLevel() . PHP_EOL;
    // }
    ?>
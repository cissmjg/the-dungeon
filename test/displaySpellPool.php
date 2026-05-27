<?php
    require_once __DIR__ . '/../classes/playerCharacterPoolSpell.php';
    require_once __DIR__ . '/../classes/playerCharacterSpellPool.php';

    $character_name = $argv[1];
    if (empty($character_name)) {
        echo 'Character name is required' . PHP_EOL;
        exit;
    }

    $spell_pool_file_name = sprintf("data/%s_spell_pool.json", $character_name);
    $spell_pool_json = json_decode(file_get_contents($spell_pool_file_name));

    $player_character_spell_pool = new PlayerCharacterSpellPool();
    $player_character_spell_pool->fromJSON($spell_pool_json->playerCharacterPoolSpellList);

    foreach ($player_character_spell_pool->getSpellMap() as $character_class_name => $spellsByClass) {
        echo 'Character Class: ' . $character_class_name . PHP_EOL;
        foreach($spellsByClass as $spell_type => $spellsByClassType) {
            echo '  Spell Type: ' . $spell_type . PHP_EOL;
            foreach($spellsByClassType as $spell_level => $spellsByClassTypeLevel) {
                echo '    Spell Level: ' . $spell_level . PHP_EOL;
                $options_for_level = $player_character_spell_pool->getOptions($character_class_name, $spell_type, $spell_level);
                foreach($options_for_level AS $spell_pool_option) {
                    echo $spell_pool_option;
                }
                // foreach($spellsByClassTypeLevel AS $pool_spell) {
                    //echo '      Spell Name: ' . $pool_spell->getSpellName() . ' level: ' . $pool_spell->getSpellLevel() . PHP_EOL;
                // }
            }
        }
    }

    // foreach($player_character_ready_spell_set->getAll() AS $player_character_ready_spell) {
    //    echo 'Spell Name: ' . $player_character_ready_spell->getSpellName() . ' level: ' . $player_character_ready_spell->getPlayerSlotLevel() . PHP_EOL;
    // }
    ?>
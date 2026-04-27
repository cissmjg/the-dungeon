<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/SpellCalculationHelper.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

require_once __DIR__ . '/../classes/characterSummary.php';

// Get player and Character Name
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
	die(json_encode($errors));
}

$all_spells_in_spellbook = getSpellBookForGreaterMage($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$character_classes = $character_summary->getCharacterClasses();
$character_level = $character_classes[0]['character_level'];

// Since Greater Mages can ONLY be a single class character (i.e. no dual class, no multi-class), $character_classes in $character_summary will only have 1 entry
$all_spells = normalizeSpells($all_spells_in_spellbook, $character_level);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($all_spells);
}

function getSpellBookForGreaterMage(\PDO $pdo, $player_name, $character_name, &$errors) {
	$sql_exec = "CALL getSpellBookForGreaterMage(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getSpellBookForGreaterMage : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function normalizeSpells($spells_for_class, $character_level) {
	$normalized_spells_for_class = [];
	foreach($spells_for_class AS $spell_for_class) {
		$normalized_spell_for_class = [];
		$normalized_spell_for_class['player_spell_pool_id'] = $spell_for_class['player_spell_pool_id'];
		$normalized_spell_for_class['spell_catalog_id'] = $spell_for_class['spell_catalog_id'];
        $normalized_spell_for_class['spell_name'] = $spell_for_class['spell_name'];
		$normalized_spell_for_class['spell_link'] = $spell_for_class['spell_link'];
		$normalized_spell_for_class['spell_level'] = $spell_for_class['spell_level'];
		$normalized_spell_for_class['spell_casting_time'] = SpellCalculationHelper::getSpellCastingTime($spell_for_class);
		$normalized_spell_for_class['spell_range'] = SpellCalculationHelper::getSpellRange($spell_for_class, $character_level);
		$normalized_spell_for_class['spell_duration'] = SpellCalculationHelper::getSpellDuration($spell_for_class, $character_level);
		$normalized_spell_for_class['spell_area_of_effect'] = $spell_for_class['spell_area_of_effect'];

		$cast_time_exceeds_round = $spell_for_class['spell_casting_time_in_rounds'] != NULL;
        // Calculate spell duration in terms of rounds 
        $spell_duration_in_rounds = SpellCalculationHelper::calculateDurationInRounds($character_level, $spell_for_class['spell_duration_time_fixed'], $spell_for_class['spell_duration_time_fixed_uom'], $spell_for_class['spell_duration_time_per_level'], $spell_for_class['spell_duration_time_per_level_uom'], $spell_for_class['spell_duration_level_factor'], $cast_time_exceeds_round);
        if($spell_duration_in_rounds != 0) {
            $normalized_spell_for_class['spell_duration_in_rounds'] = $spell_duration_in_rounds;
        }

        if($spell_for_class['spell_casting_time_in_rounds'] != NULL) {
            $normalized_spell_for_class['spell_casting_time_in_rounds'] = $spell_for_class['spell_casting_time_in_rounds'];
        }

		$normalized_spells_for_class[] = $normalized_spell_for_class;
	}

	return $normalized_spells_for_class;
}

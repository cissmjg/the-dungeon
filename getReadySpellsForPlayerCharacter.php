<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'spellCalculations.php';
require_once 'timeUnitOfMeasure.php';

// Get player and Character Name
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$all_spells = getAllSpells($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($all_spells);
}

function getAllSpells($pdo, $player_name, $character_name, &$errors) {
	$all_spells = [];
	$character_classes = getCharacterClasses($pdo, $player_name, $character_name, $errors);
	foreach($character_classes AS $character_class) {
		$character_class_name = $character_class['class_name'];
		$spells_for_class = getReadySpells($pdo, $player_name, $character_name, $character_class_name, $errors);
		if (count($spells_for_class) > 0) {
			// 'Normalize' Casting time, Duration, Range and spellcaster adjusted level
			$normalized_spells_for_class = normalizeReadySpells($spells_for_class, $character_class);
			$all_spells[] = $normalized_spells_for_class;
		}
	}

	return $all_spells;
}
		
function getCharacterClasses(\PDO $pdo, $player_name, $character_name, &$errors) {
	$sql_exec = "CALL getCharacterClasses(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getCharacterClasses : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getReadySpells(\PDO $pdo, $player_name, $character_name, $character_class_name, &$errors) {
	$sql_exec = "CALL getReadySpells(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getReadySpells : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function normalizeReadySpells($spells_for_class, $character_class) {
	$normalized_spells_for_class = [];
	foreach($spells_for_class AS $spell_for_class) {
        $character_level = getAdjustedCasterLevel($character_class, $character_class['character_level'], $spell_for_class['player_slot_spell_type_id']);
		$normalized_spell_for_class = [];
		$normalized_spell_for_class['spell_type'] = $spell_for_class['spell_type'];
		$normalized_spell_for_class['player_slot_level'] = $spell_for_class['player_slot_level'];
        $normalized_spell_for_class['player_slot_spell_slot_type_id'] = $spell_for_class['player_slot_spell_slot_type_id'];
        $normalized_spell_for_class['spell_name'] = $spell_for_class['spell_name'];
		$normalized_spell_for_class['spell_link'] = $spell_for_class['spell_link'];
		$normalized_spell_for_class['spell_slot_id'] = $spell_for_class['spell_slot_id'];
		$normalized_spell_for_class['has_spell_cast'] = $spell_for_class['has_spell_cast'];
		$normalized_spell_for_class['character_class_name'] = $spell_for_class['character_class_name'];
		$normalized_spell_for_class['spell_casting_time'] = getSpellCastingTime($spell_for_class);
		$normalized_spell_for_class['spell_range'] = getSpellRange($spell_for_class, $character_level);
		$normalized_spell_for_class['spell_duration'] = getSpellDuration($spell_for_class, $character_level);
		$normalized_spell_for_class['spell_area_of_effect'] = $spell_for_class['spell_area_of_effect'];
		$normalized_spell_for_class['player_slot_casting_time_remaining'] = $spell_for_class['player_slot_casting_time_remaining'];
		$normalized_spell_for_class['player_slot_running_time_remaining'] = $spell_for_class['player_slot_running_time_remaining'];

        // Calculate spell duration in terms of rounds
        $spell_duration_in_rounds = calculateDurationInRounds($character_level, $spell_for_class['spell_duration_time_fixed'], $spell_for_class['spell_duration_time_fixed_uom'], $spell_for_class['spell_duration_time_per_level'], $spell_for_class['spell_duration_time_per_level_uom'], $spell_for_class['spell_duration_level_factor']);
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

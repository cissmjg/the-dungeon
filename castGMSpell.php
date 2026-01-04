<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/spellCatalogId.php';
require_once __DIR__ . '/webio/spellLevel.php';
require_once __DIR__ . '/webio/spellDuration.php';
require_once __DIR__ . '/webio/spellCastingTime.php';
require_once 'characterSummary.php';

require_once 'spellSlotTypes.php';

$log = [];
$errors = [];

// Get player name
getPlayerName($errors, $input);

// Get character name	
getCharacterName($errors, $input);

// Get the Spell Catalog ID of the spell being cast
getSpellCatalogId($errors, $input);

// Get the Spell Level of the spell being cast
getSpellLevel($errors, $input);

// Get the Spell Duration of the spell being cast
getSpellDuration($errors, $input);

// Get the Spell Casting time of the spell being cast
getSpellCastingTime($errors, $input);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];
$spell_catalog_id = $input[SPELL_CATALOG_ID];
$spell_level = $input[SPELL_LEVEL];
$spell_duration = $input[SPELL_DURATION];
$spell_casting_time = $input[SPELL_CASTING_TIME];

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $player_name, $character_name);

$character_class_id = getCharacterClassId($character_summary->getCharacterClasses());

$log[] = "SUCCESS|";

$extra_slot_result =  allocateGMExtraSpellSlot($pdo, $character_class_id, $spell_catalog_id, $spell_level, $errors);
$extra_slot_id = $extra_slot_result['player_spell_slot_id'];

setSlotCastTimeAndDuration($pdo, $extra_slot_id, $spell_casting_time, $spell_duration, $errors);

// Set the slot status to cast = true
setSlotCastStatus($pdo, $extra_slot_id, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function allocateGMExtraSpellSlot(\PDO $pdo, $player_character_class_id, $spell_catalog_id, $slot_level, &$errors) {
	$sql_exec = "CALL allocateGMExtraSpellSlot(:playerCharacterClassId, :spellCatalogId, :slotLevel, :spellTypeId)";

    $slot_type_id = GM_EXTRA_SLOT_SKILL_SLOT_TYPE;
    $statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':spellCatalogId', $spell_catalog_id, PDO::PARAM_INT);
	$statement->bindParam(':slotLevel', $slot_level, PDO::PARAM_INT);
	$statement->bindParam(':spellTypeId', $slot_type_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in allocateGMExtraSpellSlot : " . $e->getMessage();
	}

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function setSlotCastTimeAndDuration(\PDO $pdo, $spell_slot_id, $casting_time, $spell_duration, &$errors) {
	$sql_exec = "CALL setSlotCastTimeAndDuration(:spellSlotId, :castingTime, :durationTime)";

    $statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':spellSlotId', $spell_slot_id, PDO::PARAM_INT);
	$statement->bindParam(':castingTime', $casting_time, PDO::PARAM_INT);
	$statement->bindParam(':durationTime', $spell_duration, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in setSlotCastTimeAndDuration : " . $e->getMessage();
	}
}

function setSlotCastStatus(\PDO $pdo, $extra_slot_id, &$errors) {
	$sql_exec = "CALL setSlotCastStatus(:spellSlotId, :castStatus)";

	$cast_status = true;
    $statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':spellSlotId', $extra_slot_id, PDO::PARAM_INT);
	$statement->bindParam(':castStatus', $cast_status, PDO::PARAM_BOOL);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in setSlotCastStatus : " . $e->getMessage();
	}
}

function getCharacterClassId($character_classes) {
    foreach($character_classes AS $character_class) {
        return $character_class['player_character_class_id'];
    }
}
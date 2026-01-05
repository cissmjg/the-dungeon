<?php

$log = [];
$errors = [];
$input = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/castStatus.php';
require_once __DIR__ . '/webio/spellSlotId.php';
require_once __DIR__ . '/webio/spellDuration.php';
require_once __DIR__ . '/webio/spellCastingTime.php';

// Get Spell Slot ID
getSpellSlotId($errors, $input);

// Cast Status
getCastStatus($errors, $input);

// Spell Duration
getSpellCastingTime($errors, $input);

// Spell Casting Time
getSpellDuration($errors, $input);

$spell_slot_id = $input[SPELL_SLOT_ID];
$cast_status = $input[CAST_STATUS];
$spell_casting_time = $input[SPELL_CASTING_TIME];
$spell_duration = $input[SPELL_DURATION];

$log[] = "SUCCESS|";
$log[] = "Slot ID: " . $spell_slot_id;
$log[] = "cast status : " . $cast_status;
$log[] = "spell duration : " . $spell_duration;
$log[] = "spell casting time : " . $spell_casting_time;
setSlotCastStatus($pdo, $spell_slot_id, $cast_status, $errors);
setSlotCastingTimeAndDuration($pdo, $spell_slot_id, $spell_casting_time, $spell_duration, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function setSlotCastStatus(\PDO $pdo, $spell_slot_id, $cast_status, &$errors) {
	$sql_exec = "CALL setSlotCastStatus(:spellSlotId, :castStatus)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':spellSlotId', $spell_slot_id, PDO::PARAM_INT);
	$statement->bindParam(':castStatus', $cast_status, PDO::PARAM_BOOL);	
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in setSlotCastStatus : " . $e->getMessage();
	}
}

function setSlotCastingTimeAndDuration(\PDO $pdo, $spell_slot_id, $casting_time, $duration_time, &$errors) {
	$sql_exec = "CALL setSlotCastTimeAndDuration(:spellSlotId, :castingTime, :durationTime)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':spellSlotId', $spell_slot_id, PDO::PARAM_INT);
	$statement->bindParam(':castingTime', $casting_time, PDO::PARAM_INT);
	$statement->bindParam(':durationTime', $duration_time, PDO::PARAM_INT);

	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in setSlotCastTimeAndDuration : " . $e->getMessage();
	}
}
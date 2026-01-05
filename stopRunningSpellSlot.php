<?php

$log = [];
$errors = [];
$input = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/spellSlotId.php';
require_once __DIR__ . '/webio/spellDuration.php';
require_once __DIR__ . '/webio/spellCastingTime.php';

// Get Spell Slot ID
getSpellSlotId($errors, $input);

// Spell Duration
getSpellCastingTime($errors, $input);

// Spell Casting Time
getSpellDuration($errors, $input);

$spell_slot_id = $input[SPELL_SLOT_ID];
$spell_casting_time = $input[SPELL_CASTING_TIME];
$spell_duration = $input[SPELL_DURATION];

$log[] = "SUCCESS|";
$log[] = "Slot ID: " . $spell_slot_id;
$log[] = "spell duration : " . $spell_duration;
$log[] = "spell casting time : " . $spell_casting_time;
setSlotCastingTimeAndDuration($pdo, $spell_slot_id, $spell_casting_time, $spell_duration, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
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
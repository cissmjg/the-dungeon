<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/spellSlotId.php';

$log = [];
$errors = [];

getSpellSlotId($errors, $input);

$log[] = "SUCCESS|";
reclaimCantripSlots($pdo, $input[SPELL_SLOT_ID], $errors);

RestHeaderHelper::emitRestHeaders();

if (count($errors) > 0) {
	echo json_encode($errors);	
} else {
	echo json_encode($log);
}

function reclaimCantripSlots(\PDO $pdo, $ready_spell_slot_id, &$errors) {
	$sql_exec = "CALL deallocateCantripsForSlot(:parentSpellSlotId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':parentSpellSlotId', $ready_spell_slot_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in reclaimCantripSlots : " . $e->getMessage();
	}
}
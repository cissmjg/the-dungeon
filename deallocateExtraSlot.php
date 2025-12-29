<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once 'RestHeaderHelper.php';
require_once 'spellSlotId.php';

$log = [];
$errors = [];

getSpellSlotId($errors, $input);

$log[] = "SUCCESS|";
deallocateExtraSlot($pdo, $input['spellSlotId'], $errors);

RestHeaderHelper::emitRestHeaders();

if (count($errors) > 0) {
	echo json_encode($errors);	
} else {
	echo json_encode($log);
}

function deallocateExtraSlot(\PDO $pdo, $extra_slot_id, &$errors) {
	$sql_exec = "CALL deallocateExtraSlot(:extraSlotId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':extraSlotId', $extra_slot_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deallocateExtraSlot : " . $e->getMessage();
	}
}
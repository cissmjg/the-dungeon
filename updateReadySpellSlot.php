<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'spellCatalogId.php';
require_once 'spellSlotId.php';

$log = [];
$errors = [];

getSpellSlotId($errors, $input);
getSpellCatalogId($errors, $input);

$log[] = "SUCCESS|";
updateReadySpellSlot($pdo, $input[SPELL_CATALOG_ID], $input['spellSlotId'], $errors);

RestHeaderHelper::emitRestHeaders();

if (count($errors) > 0) {
	echo json_encode($errors);	
} else {
	echo json_encode($log);
}

function updateReadySpellSlot(\PDO $pdo, $spell_catalog_id, $ready_spell_slot_id, &$errors) {
	$sql_exec = "CALL updateReadySpellSlot(:spellCatalogId, :spellSlotId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':spellCatalogId', $spell_catalog_id, PDO::PARAM_INT);
	$statement->bindParam(':spellSlotId', $ready_spell_slot_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateReadySpellSlot : " . $e->getMessage();
	}
}
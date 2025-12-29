<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once 'RestHeaderHelper.php';
require_once 'spellCatalogId.php';
require_once 'spellPoolSlotId.php';

$log = [];
$errors = [];

// Filter and sanitize IDs
getSpellCatalogId($errors, $input);
getSpellPoolSlotId($errors, $input);

$spell_catalog_id = $input['spellCatalogId'];
$spell_pool_slot_id = $input['spellPoolSlotId'];

$log[] = "SUCCESS|";
updateSpellPoolSlot($pdo, $spell_catalog_id, $spell_pool_slot_id, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function updateSpellPoolSlot(\PDO $pdo, $spell_catalog_id, $spell_pool_slot_id, &$errors) {
	$sql_exec = "CALL updateSpellPoolSlot(:spellCatalogId, :spellPoolSlotId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':spellCatalogId', $spell_catalog_id, PDO::PARAM_INT);
	$statement->bindParam(':spellPoolSlotId', $spell_pool_slot_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateSpellPoolSlot : " . $e->getMessage();
	}
}